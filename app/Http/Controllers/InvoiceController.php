<?php

namespace App\Http\Controllers;

use App\BkashTokenizedCheckout;
use App\Curl;
use App\Models\Book;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\SalesMatric;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInvoice;
use App\Notifications\InvoiceProcessing;
use App\Notifications\InvoiceVendor;
use App\Winx;
use Auth;
use DB;
use DGvai\Nagad\Facades\Nagad;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class InvoiceController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [1200, 400];
        $this->image_md     = [300, 300];
        $this->image_sm     = [300, 300];
        $this->image_column = 'column';
    }

    /**
     * Save image with redactor driver
     * Saves image in 3 size + main source
     *
     * @param  \Illuminate\Http\Request  $request (image)
     * @return [type]        [description]
     */
    public function saveImage($image, $input = [])
    {
        $path = $image->store('redactor', 'redactor');

        $lg      = Image::make(public_path('assets/images/' . $path))->resize($this->image_lg[0], $this->image_lg[1]);
        $lg_path = public_path('assets/images/' . str_replace("redactor/", "redactor/lg_", $path));
        $lg->save($lg_path, 100);

        $md      = Image::make(public_path('assets/images/' . $path))->resize($this->image_md[0], $this->image_md[1]);
        $md_path = public_path('assets/images/' . str_replace("redactor/", "redactor/md_", $path));
        $md->save($md_path, 100);

        $sm      = Image::make(public_path('assets/images/' . $path))->resize($this->image_sm[0], $this->image_sm[1]);
        $sm_path = public_path('assets/images/' . str_replace("redactor/", "redactor/sm_", $path));
        $sm->save($sm_path, 30);

        $input[$this->image_column] = url('assets/images/' . $path);
        return $input;
    }

    /**
     * Display a listing of the resource.
     * Datatable Ajax & Html
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $builder)
    {
        $type    = 'All';
        $auth    = Auth::user();
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'addresses.name', 'title' => 'Name'],
            ['data' => 'mobile', 'name' => 'addresses.mobile', 'title' => 'Mobile'],
            ['data' => 'email', 'name' => 'addresses.email', 'title' => 'Email'],
            ['data' => 'total', 'name' => 'total', 'title' => 'Total', 'class' => 'text-center', 'render' => 'Number(full.total) + Number(full.shipping) + Number(full.gift_wrap) - Number(full.coupon_discount)'],
            ['data' => 'payment', 'name' => 'payment', 'title' => 'Payment', 'class' => 'text-center'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'print', 'name' => 'print', 'title' => 'Print', 'render' => 'window.invoice_print(full.print)', 'class' => 'text-center'];
        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.invoice_status(full.status)', 'class' => 'text-center'];
        $columns[] = ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Order Date'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('invoice', ['delete', 'edit'], ['print'])];

        if ($request->ajax()) {
            $query    = DB::table('invoices')->where('invoices.status', '>', 0);
            $_columns = ['addresses.name as name', 'addresses.mobile as mobile', 'addresses.email as email', 'invoices.shipping', 'invoices.gift_wrap', 'invoices.coupon_discount', 'invoices.user_id'];
            // $_columns = ['users.name as name', 'users.mobile as mobile', 'users.email as email', 'invoices.shipping', 'invoices.gift_wrap', 'invoices.coupon_discount', 'invoices.user_id'];

            $status = ["-", "pending", "processing", "shipping", "complete", "cancelled", "refunded", 'packed'];
            if (in_array($request->status, $status)) {
                $query = $query->where('invoices.status', array_search($request->status, $status));
            }
            if ($request->status == 'cod') {
                $query = $query->where('invoices.status', 1)->where('invoices.payment', 'cod');
            }
            if ($request->status == 'half-packed') {
                $query = $query->where('invoices.packed', '>', 0)->where('invoices.packed', '<', 100);
            }
            if ($request->status == 'pre-order') {
                $query = $query->where('invoices.pre_order', 1);
            } else {
                $query = $query->where('invoices.pre_order', 0);
            }
            $query = $query->join('addresses', 'invoices.shipping_id', '=', 'addresses.id');
            // $query = $query->join('users', 'invoices.user_id', '=', 'users.id');

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'name', 'mobile', 'email'])) {
                    $_columns[] = "invoices." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $export = (in_array($auth->role, ['admin', 'operations'])) ? "exportButton();" : '';
        $html   = $builder->columns($columns)
            ->parameters([
                'pagingType'      => 'full_numbers',
                'responsive'      => true,
                'order'           => [0, 'desc'],
                'searchHighlight' => true,
                'pageLength'      => 25,
                "drawCallback"    => "function(){ " . $export . " }",
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        $('td:eq(1)', row).html('<a href=\"/customer/' + data.user_id + '\">' + data.name + '</a>');
                        $('td:eq(2)', row).html('<a href=\"tel://' + data.mobile + '\">' + data.mobile + '</a>');
                        $('td:eq(3)', row).html('<a href=\"mailto://' + data.email + '\">' + data.email + '</a>');
                    }",
            ]);

        return view('invoice.index')
            ->with('type', ucfirst($request->get('status', 'All')))
            ->with('html', $html);
    }

    /**
     * Display a listing of the resource in select2 formate (no pagination).
     * Special Search Feature ID:123 will return one item from given id
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $query = DB::table('invoices');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['col_1', 'col_2'], $q);
            }
        }
        $items         = $query->select(['id', 'col_1', 'col_2'])->take(30)->get()->toArray();
        $re['results'] = $items;
        return $re;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            '' => 'required',
        ]);

        $input = $request->except(['']);

        // Use to save image
        // if ($request->image) {
        //     $input = $this->saveImage($request->image,$input);
        // }

        $item = Invoice::create($input);

        return redirect('admin/invoice')->with('success', 'New Invoice Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Invoice $invoice)
    {
        if ($invoice->status == 0) {return abort(404);}
        $status_class = ["", "", "InvoiceProcessing", "InvoiceShipped", "InvoiceCompleted", "InvoiceCanceled", "InvoiceRefunded"];
        if ($request->mail && $status_class[$request->mail]) {
            $c_n  = "App\Notifications\\" . $status_class[$request->mail];
            $user = User::where('id', $invoice->user_id)->first();
            $user->notify(new $c_n($user, $invoice));
            return redirect('invoice/' . $invoice->id)->with('success', $status_class[$request->mail] . " Mail & SMS Sent!");
        }

        if ($request->print == 'yes') {
            $invoice->print = 1;
            $invoice->save();
            return $this->success('**ok**');
        }
        if ($invoice->lock_by > 0 && $invoice->lock_by != Auth::id()) {
            return view('invoice.show')->with('item', $invoice)->with('lock', true);
        }
        return view('invoice.show')->with('item', $invoice);
    }

    function print(Request $request, $invoice) {
        $invoice = Invoice::findorfail($invoice);
        return view('invoice.show')->with('item', $invoice)->with('print', true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $invoice->password = '';
        return view('invoice.edit')->with('item', $invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

        $user         = Auth::user();
        $status       = ["-", "pending", "processing", "shipping", "completed", "cancelled", "refunded", 'packed'];
        $status_class = ["", "", "InvoiceProcessing", "InvoiceShipped", "InvoiceCompleted", "InvoiceCanceled", "InvoiceRefunded", ""];

        $invoice->processed_by = $user->id;
        if ($request->lock == 'yes') {
            $invoice->lock_by = $user->id;
            $invoice->lock_at = date('Y-m-d H:i:s');
            $invoice->save();
            return $this->success('**locked**');
        }
        if ($request->lock == 'no') {
            $invoice->lock_by = 0;
            $invoice->lock_at = null;
            $invoice->save();
            return $this->success('**unlocked**');
        }

        if ($request->system_note) {
            $sn                   = $invoice->system_note;
            $sn[]                 = $user->name . " >> " . $request->system_note;
            $invoice->system_note = $sn;
            $invoice->save();
        }

        if ($request->tracking != $invoice->tracking) {
            $invoice->tracking = $request->tracking;
            $invoice->save();
        }

        if ($request->package) {
            $request->validate([
                'pickup_id'     => 'required',
                'delivery_area' => 'required',
                'package'       => 'required',
            ]);

            $winx  = new Winx();
            $cod   = $paid   = 0;
            $total = $invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount;
            $cod   = $total;
            if ($invoice->partial_payment > 0) {$cod = $total - $invoice->partial_payment;}
            $sn = json_encode($invoice->system_note);
            if (preg_match("/sslcommerz_card\#([0-9a-z_\|\s,\.]+)\"/i", $sn, $match)) {
                $paid = true;
                $paid = ($invoice->partial_payment > 0) ? false : true;
            }
            if (preg_match("/bkash\#/i", $sn, $match)) {
                $paid = true;
                $paid = ($invoice->partial_payment > 0) ? false : true;
            }
            if (preg_match("/nagad\#/i", $sn, $match)) {
                $paid = true;
                $paid = ($invoice->partial_payment > 0) ? false : true;
            }
            if ($paid) {$cod = 0;} else { $cod = $total - $invoice->partial_payment;}
            if (!@$invoice->shipping_address->street) {return redirect('/invoice/' . $invoice->id)->with('error', 'Shipping Address Missing!');}
            $address = [$invoice->shipping_address->street, $invoice->shipping_address->district, $invoice->shipping_address->city . ' - ' . $invoice->shipping_address->postcode, $invoice->shipping_address->country];
            $address = @implode(', ', array_filter($address));
            // var_dump($invoice->tracking);
            // var_dump($cod);
            // dd([$invoice->id, $request->pickup_id, $invoice->shipping_address->name, $invoice->shipping_address->mobile, $address, $request->package, $request->delivery_area, $total, $cod, false]);

            // implode(", ", array_filter([@$invoice->shipping_address->mobile,@$invoice->shipping_address->mobile2])) }}
            $result = $winx->createOrder($invoice->id, $request->pickup_id, $invoice->shipping_address->name, $invoice->shipping_address->mobile, $address, $request->package, $request->delivery_area, $total, $cod, false);
            if ($result && @$result->tracking) {
                $invoice->tracking = 'WINX:' . @$result->tracking;
                $invoice->save();
                $this->shipped($invoice->id);
                return redirect('/invoice/' . $invoice->id)->with('success', 'Parcel Booked in WINX.');
            } else {
                return redirect('/invoice/' . $invoice->id)->with('error', 'Parcel Booking failed with WINX.');
            }
        }

        if ($request->partial_payment && $user->role == 'admin') {
            $request->validate([
                'partial_amount'  => 'required',
                'partial_payment' => 'required',
            ]);
            $__pp = Invoice::where('system_note', "LIKE", "%{$request->partial_payment}%")->first();
            if ($__pp) {
                return redirect('invoice/' . $invoice->id)->with('error', 'TrxID Found in Inv#' . $__pp->id);
            }
            $sn                       = $invoice->system_note;
            $sn[]                     = $user->name . " >> PP# " . $request->partial_payment;
            $invoice->system_note     = $sn;
            $invoice->partial_payment = $request->partial_amount;
            $invoice->shipping        = (int) $request->shipping;
            $invoice->save();
        }

        if ($request->payment && $invoice->partial_payment <= 0 && $invoice->status != 4) {
            $invoice->payment = $request->payment;
            $invoice->save();
        }

        if ($request->status && $request->status != $invoice->status) {
            if (!in_array($invoice->status, [2, 4]) && $request->status == 6) {
                return redirect('invoice/' . $invoice->id)->with('error', 'Invoice is not refundable!');
            }
            $sn                   = $invoice->system_note;
            $sn[]                 = $user->name . " >> Status " . ucfirst($status[$invoice->status]) . ' -> ' . ucfirst($status[$request->status]);
            $invoice->system_note = $sn;
            $invoice->status      = $request->status;
            $invoice->save();

            VendorInvoice::where('invoice_id', $invoice->id)->update(['status' => $invoice->status]);

            if ($status_class[$invoice->status]) {
                $c_n  = "App\Notifications\\" . $status_class[$invoice->status];
                $user = User::where('id', $invoice->user_id)->first();
                $user->notify(new $c_n($user, $invoice));
                if (preg_match("/processing/i", $c_n)) {
                    $this->callProcessing($invoice);
                }
                if (preg_match("/completed/i", $c_n)) {
                    $this->callDelivery($invoice);
                }
            }

            /**
             * Product Stock Update
             * @var [type]
             */
            if (in_array($invoice->status, [2, 3, 4]) && $invoice->stock_update == 0) {
                foreach ($invoice->metas as $key => $meta) {
                    $product = ($meta->book_id) ? Book::find($meta->book_id)->decrement('stock', $meta->quantity) : Product::find($meta->product_id)->decrement('stock', $meta->quantity);
                    $product = ($meta->book_id) ? Book::find($meta->book_id) : Product::find($meta->product_id);
                    if ($meta->book_id) {
                        if ($meta->book->actual_stock <= 0) {
                            $req = Requisition::where('product_id', $meta->book_id)->first();
                            if ($req) {
                                $req->quantity += $meta->quantity;
                                $req->save();
                            } else {
                                $req             = new Requisition();
                                $req->quantity   = $meta->quantity;
                                $req->product_id = $meta->book_id;
                                $req->save();
                            }
                        }
                        Book::find($meta->book_id)->decrement('actual_stock', $meta->quantity);
                    }
                    $sm = ['author_id' => ($meta->book_id) ? $product->author_id : 0, 'book_id' => ($meta->book_id) ? $meta->book_id : 0, 'vendor_id' => $meta->vendor_id, 'product_id' => ($meta->book_id) ? 0 : $meta->product_id, 'created_at' => date('Y-m-d')];
                    SalesMatric::create($sm);
                }
                $invoice->stock_update = 1;
                $invoice->save();
            }

            /**
             * Make Payment Entry
             * @var [type]
             */
            if ($invoice->status == 4 && $invoice->stock_update < 2) {
                foreach ($invoice->metas as $key => $meta) {
                    $amount     = $meta->rate * $meta->quantity;
                    $mrp_amount = $meta->product->rate * $meta->quantity;
                    $vendor     = Vendor::find($meta->vendor_id);
                    $pg_fee     = $amount * 0.03;
                    $fee        = (($mrp_amount * $vendor->fee) / 100) - $pg_fee;
                    Payment::create(['vendor_id' => $meta->vendor_id, 'invoice_id' => $invoice->id, 'amount' => $amount, 'fee' => $fee, 'pg_fee' => $pg_fee, 'method' => $invoice->payment, 'status' => 0]);
                }
                $invoice->stock_update = 2;
                $invoice->save();
            }

            /**
             * Make Refund Entry
             */
            if ($invoice->status == 6 && $invoice->stock_update < 3) {
                foreach ($invoice->metas as $key => $meta) {
                    $amount     = $meta->rate * $meta->quantity;
                    $mrp_amount = $meta->product->rate * $meta->quantity;
                    $vendor     = Vendor::find($meta->vendor_id);
                    $pg_fee     = $amount * 0.03;
                    $fee        = (($mrp_amount * $vendor->fee) / 100) - $pg_fee;
                    Payment::create(['vendor_id' => $meta->vendor_id, 'invoice_id' => $invoice->id, 'amount' => $amount, 'fee' => $fee, 'pg_fee' => $pg_fee, 'method' => $invoice->payment, 'status' => 0, 'type' => 1]);
                }
                $invoice->stock_update = 3;
                $invoice->save();
            }
        }

        return redirect('invoice/' . $invoice->id)->with('success', 'Invoice Details Updated!');
        $input = $request->except(['']);

        // Use to save image
        // if ($request->image) {
        //     $input = $thos->saveImage($request->image, $input);
        // }

        $invoice->update($input);

        return redirect('admin/invoice')->with('success', 'Invoice Updated!');
    }

    /**
     * Invoice Processing
     * @param  [type] $invoice [description]
     * @return [type]          [description]
     */
    public function callProcessing($invoice)
    {
        $vendors = array_unique($invoice->metas->pluck('vendor_id')->toArray());
        $vendors = Vendor::whereIn('id', $vendors)->get();
        $user    = User::find($invoice->user_id);
        VendorInvoice::where('invoice_id', $invoice->id)->update(['status' => $invoice->status]);
        foreach ($vendors as $key => $vendor) {
            if ('xx' == 'yy') {
                $user->notify(new InvoiceVendor($user, $invoice, $vendor->email));
            }
        }
    }

    /**
     * Call Invoice Delivery
     * @param  [type] $invoice [description]
     * @return [type]          [description]
     */
    public function callDelivery($invoice)
    {
        $invoice->delivery_date = date("Y-m-d");
        $invoice->save();
    }

    /**
     * SSL Callback
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentSSL(Request $request)
    {
        if ($request->tran_id) {
            $trns    = preg_replace('/(_.*)/', '', $request->tran_id);
            $invoice = Invoice::findorFail($trns);
            if (!$request->val_id) {return redirect('my-account/order/' . $invoice->id)->with('error', 'Payment Processing Failed, Please Try Again!');}
            if ($invoice->status > 1) {return abort(404);}
            $total                = $invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount;
            $ch                   = new Curl();
            $post['val_id']       = $request->val_id;
            $post['store_id']     = config('services.ssl.store_id');
            $post['store_passwd'] = config('services.ssl.store_passwd');
            $post['format']       = 'json';
            $data                 = json_decode($ch->get(config('services.ssl.check') . '?' . http_build_query($post)));

            if ($data) {
                if (in_array($data->status, ['VALID', 'VALIDATED']) && $data->amount >= $total && $invoice->status < 2) {
                    $invoice->status      = "2";
                    $system_note          = $invoice->system_note;
                    $system_note[]        = 'sslcommerz#' . $request->val_id;
                    $system_note[]        = 'sslcommerz_trns#' . $request->tran_id;
                    $system_note[]        = 'sslcommerz_card#' . $request->card_issuer . " | " . $request->card_brand . '|' . $request->card_issuer_country;
                    $invoice->system_note = $system_note;
                    $invoice->save();
                    // Send Mail
                    $user = User::find($invoice->user_id);
                    $user->notify(new InvoiceProcessing($user, $invoice));
                    $this->callProcessing($invoice);
                    return redirect('my-account/order/' . $invoice->id . "?payment=success&msg=We received your payment. Your order is now processing.");
                }
                if (in_array($data->status, ['VALID', 'VALIDATED']) && $data->amount >= 50 && $invoice->status < 2) {
                    $invoice->status          = "2";
                    $invoice->partial_payment = $data->amount;
                    $system_note              = $invoice->system_note;
                    $system_note[]            = 'sslcommerz#' . $request->val_id;
                    $system_note[]            = 'sslcommerz_trns#' . $request->tran_id;
                    $system_note[]            = 'sslcommerz_card#' . $request->card_issuer . " | " . $request->card_brand . '|' . $request->card_issuer_country;
                    $invoice->system_note     = $system_note;
                    $invoice->save();
                    // Send Mail
                    $user = User::find($invoice->user_id);
                    $user->notify(new InvoiceProcessing($user, $invoice));
                    $this->callProcessing($invoice);
                    return redirect('my-account/order/' . $invoice->id . "?payment=success&msg=We received your partial payment. Your order is now processing.");
                }
            } else {
                return redirect('my-account/order/' . $invoice->id . "?payment=failed");
            }
        }
        return abort(404);
    }

    /**
     * Process bKash Payment
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentBkash(Request $request)
    {
        if ($request->paymentID) {
            $bkash   = new BkashTokenizedCheckout();
            $data    = $bkash->executePayment($request->paymentID);
            $err_msg = (@$data->statusMessage) ? $data->statusMessage : 'Payment Processing Failed.';
            if ($data && $data->statusMessage == 'Successful') {
                $invoice = Invoice::where('id', $data->merchantInvoiceNumber)->firstOrFail();
                $total   = $invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount;
                $amount  = $data->amount;
                if ($data->transactionStatus == 'Completed') {
                    if ($amount >= $total && $invoice->status < 2) {
                        $invoice->status      = "2";
                        $system_note          = $invoice->system_note;
                        $system_note[]        = 'bKash#' . $request->paymentID . ' TrxID#' . $request->trxID;
                        $invoice->system_note = $system_note;
                        $invoice->save();
                        $user = User::find($invoice->user_id);
                        $user->notify(new InvoiceProcessing($user, $invoice));
                        $this->callProcessing($invoice);
                        return redirect('my-account/order/' . $invoice->id)->with('success', 'We received your payment. Your order is now processing.');
                    } elseif ($data->amount >= 50 && $invoice->status < 2) {
                        $invoice->status          = "2";
                        $invoice->partial_payment = $data->amount;
                        $system_note              = $invoice->system_note;
                        $system_note[]            = 'bKash#' . $request->paymentID;
                        $invoice->system_note     = $system_note;
                        $invoice->save();
                        // Send Mail
                        $user = User::find($invoice->user_id);
                        $user->notify(new InvoiceProcessing($user, $invoice));
                        $this->callProcessing($invoice);
                        return redirect('my-account/order/' . $invoice->id)->with('success', 'We received your partial payment. Your order is now processing.');
                    }
                }
                return redirect('my-account/order/' . $invoice->id)->with('error', $err_msg);
            }
            return redirect('my-account')->with('error', $err_msg);
        }
        return abort(404);
    }

    /**
     * Nagad Callback
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentNagad(Request $request)
    {
        if (preg_match("/BFQ/", $request->order_id)) {
            return redirect('https://quiz.boiferry.com/ipn/nagad?' . http_build_query($request->all()));
        }
        $order = explode("X", $request->get('order_id'));
        if (count($order) == 3) {
            $order = $invoice = Invoice::where('id', $order[1])->firstOrFail();
        } else {
            return abort(404);
        }
        $verified = Nagad::callback($request)->verify();
        if ($verified->success()) {
            $total = $invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount;
            $vr    = $verified->getVerifiedResponse();
            if ($vr['amount'] >= $total && $invoice->status < 2) {
                $invoice->status      = "2";
                $system_note          = $invoice->system_note;
                $system_note[]        = 'nagad#' . $vr['issuerPaymentRefNo'];
                $invoice->system_note = $system_note;
                $invoice->save();
                $user = User::find($invoice->user_id);
                $user->notify(new InvoiceProcessing($user, $invoice));
                $this->callProcessing($invoice);
                return redirect('my-account/order/' . $invoice->id)->with('success', 'We received your payment. Your order is now processing.');
            } elseif ($vr['amount'] >= 50 && $invoice->status < 2) {
                $invoice->status          = "2";
                $invoice->partial_payment = $vr['amount'];
                $system_note              = $invoice->system_note;
                $system_note[]            = 'nagad#' . $vr['issuerPaymentRefNo'];
                $invoice->system_note     = $system_note;
                $invoice->save();
                // Send Mail
                $user = User::find($invoice->user_id);
                $user->notify(new InvoiceProcessing($user, $invoice));
                $this->callProcessing($invoice);
                return redirect('my-account/order/' . $invoice->id)->with('success', 'We received your partial payment. Your order is now processing.');
            }
        }
        return redirect('my-account/order/' . $invoice->id)->with('error', 'Payment Processing Failed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        // $invoice->delete();
        return ''; // 204 code
    }

    public function shipped($id = false)
    {
        $user = Auth::user();
        if (!$id) {
            $id = substr(request()->barcode, 0, -1) - 1000000;
        }
        $invoice                = Invoice::findOrFail($id);
        $system_note            = $invoice->system_note;
        $system_note[]          = 'shipped_by#' . $user->name;
        $invoice->status        = 3;
        $invoice->shipment_date = date('Y-m-d');
        $invoice->system_note   = $system_note;
        $invoice->save();

        $c_n  = "App\Notifications\InvoiceShipped";
        $user = User::where('id', $invoice->user_id)->first();
        $user->notify(new $c_n($user, $invoice));

        return $this->success($invoice);
    }

    public function signQZ()
    {
        $req        = @$_REQUEST['request'];
        $privateKey = openssl_get_privatekey(file_get_contents(storage_path('private.key')));

        $signature = null;
        openssl_sign($req, $signature, $privateKey, "sha512"); // Use "sha1" for QZ Tray 2.0 and older

        if ($signature) {
            header("Content-type: text/plain");
            echo base64_encode($signature);
            exit(0);
        }

        echo '<h1>Error signing message</h1>';
        http_response_code(500);
        exit(1);
    }

}
