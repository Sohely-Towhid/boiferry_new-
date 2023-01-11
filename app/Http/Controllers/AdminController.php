<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Invoice;
use App\Models\InvoiceMeta;
use App\Models\User;
use App\Rules\isValidPassword;
use Artisan;
use Auth;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show Admin Inbox
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        if (in_array($auth->role, ['crm', 'logistics'])) {
            if ($auth->role == 'crm') {
                $cod  = Invoice::where('status', 1)->where('lock_by', 0)->where('payment', 'LIKE', 'cod%')->take(10)->get();
                $paid = Invoice::where('status', 2)->where('lock_by', 0)
                    ->where(function ($query) {
                        $query->where('partial_payment', '>', 0)
                            ->orWhere('payment', 'sslcommerz');
                    })->take(10)->get();
                return view('admin.crm')->with('cod', $cod)->with('paid', $paid)->with('role', $auth->role);
            }
            if ($auth->role == 'logistics') {
                $half = Invoice::where('status', '>', 1)->where('packed', '>', 0)->where('packed', '<', 100)->take(10)->get();
                $full = Invoice::where('status', 7)->take(10)->get();
                return view('admin.crm')->with('half', $half)->with('full', $full)->with('role', $auth->role);
            }
        }
        $users = User::groupBy('role')->select('role', DB::raw('count(*) as total'))->get();
        $user  = $product  = $book  = [];

        foreach ($users as $key => $_user) {
            $user[ucfirst($_user->role)] = $_user->total;
        }
        $blocks['user'] = ['title' => 'User Details', 'sub_title' => 'All type of user', 'list' => $user];

        $products = DB::table('products')->groupBy('status')->select('status', DB::raw('count(*) as total'))->get();
        $status   = ['Pending', 'Active', 'Removed', 'N/A'];
        foreach ($products as $key => $_product) {
            $product[$status[$_product->status]] = $_product->total;
        }
        $product['Sold Out'] = DB::table('products')->whereIn('status', [1, 3])->where('stock', 0)->count('id');

        $books  = DB::table('books')->groupBy('status')->select('status', DB::raw('count(*) as total'))->get();
        $status = ['Pending', 'Active', 'Removed', 'N/A'];
        foreach ($books as $key => $_book) {
            $book[$status[$_book->status]] = $_book->total;
        }
        $book['Sold Out'] = DB::table('books')->whereIn('status', [1, 3])->where('stock', 0)->count('id');

        $blocks['product'] = ['title' => 'Product Details', 'sub_title' => 'All listed product', 'list' => $product];
        $blocks['book']    = ['title' => 'Book Details', 'sub_title' => 'All listed book', 'list' => $book];

        /**
         * Total Report
         * @var array
         */
        $invoice  = [];
        $ts       = $tcc       = 0;
        $invoices = DB::table('invoices')->where('status', '>', 0)->groupBy('status')->select(['status', DB::raw('count(*) as total_id'), DB::raw('sum(total) as amount')])->get();
        foreach ($invoices as $inv) {
            $invoice[$inv->status] = '৳ ' . $inv->amount . " / " . $inv->total_id;
            $ts += $inv->amount;
            $tcc += $inv->total_id;
        }

        $total['sale']       = ['icon' => 'fa-file-invoice-dollar text-success', 'title' => 'Total Sale', 'value' => '৳ ' . $ts . " / " . $tcc];
        $total['pending']    = ['icon' => 'fa-file-invoice-dollar text-warning', 'title' => 'Pending Order', 'value' => (@$invoice[1]) ? @$invoice[1] : 0];
        $total['shipping']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Shipping Order', 'value' => (@$invoice[3]) ? @$invoice[3] : 0];
        $total['processing'] = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Processing Order', 'value' => (@$invoice[2]) ? @$invoice[2] : 0];
        $total['complete']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Complete Order', 'value' => (@$invoice[4]) ? @$invoice[4] : 0];
        $total['refund']     = ['icon' => 'fa-file-invoice-dollar text-danger', 'title' => 'Refund Order', 'value' => (@$invoice[6]) ? @$invoice[6] : 0];

        /**
         * Today's Report
         * @var array
         */
        $invoice  = [];
        $ts       = $tcc       = 0;
        $invoices = DB::table('invoices')->where('status', '>', 0)->whereDate('created_at', date('Y-m-d'))->groupBy('status')->select(['status', DB::raw('count(*) as total_id'), DB::raw('sum(total) as amount')])->get();
        foreach ($invoices as $inv) {
            $invoice[$inv->status] = '৳ ' . $inv->amount . " / " . $inv->total_id;
            $ts += $inv->amount;
            $tcc += $inv->total_id;
        }

        $today['sale']       = ['icon' => 'fa-file-invoice-dollar text-success', 'title' => 'Total Sale', 'value' => '৳ ' . $ts . " / " . $tcc];
        $today['pending']    = ['icon' => 'fa-file-invoice-dollar text-warning', 'title' => 'Pending Order', 'value' => (@$invoice[1]) ? @$invoice[1] : 0];
        $today['shipping']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Shipping Order', 'value' => (@$invoice[3]) ? @$invoice[3] : 0];
        $today['processing'] = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Processing Order', 'value' => (@$invoice[2]) ? @$invoice[2] : 0];
        $today['complete']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Complete Order', 'value' => (@$invoice[4]) ? @$invoice[4] : 0];
        $today['refund']     = ['icon' => 'fa-file-invoice-dollar text-danger', 'title' => 'Refund Order', 'value' => (@$invoice[6]) ? @$invoice[6] : 0];

        /**
         * Yesterday's Report
         * @var array
         */
        $invoice  = [];
        $ts       = $tcc       = 0;
        $invoices = DB::table('invoices')->where('status', '>', 0)->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->groupBy('status')->select(['status', DB::raw('count(*) as total_id'), DB::raw('sum(total) as amount')])->get();
        foreach ($invoices as $inv) {
            $invoice[$inv->status] = '৳ ' . $inv->amount . " / " . $inv->total_id;
            $ts += $inv->amount;
            $tcc += $inv->total_id;
        }

        $yesterday['sale']       = ['icon' => 'fa-file-invoice-dollar text-success', 'title' => 'Total Sale', 'value' => '৳ ' . $ts . " / " . $tcc];
        $yesterday['pending']    = ['icon' => 'fa-file-invoice-dollar text-warning', 'title' => 'Pending Order', 'value' => (@$invoice[1]) ? @$invoice[1] : 0];
        $yesterday['shipping']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Shipping Order', 'value' => (@$invoice[3]) ? @$invoice[3] : 0];
        $yesterday['processing'] = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Processing Order', 'value' => (@$invoice[2]) ? @$invoice[2] : 0];
        $yesterday['complete']   = ['icon' => 'fa-file-invoice-dollar text-info', 'title' => 'Complete Order', 'value' => (@$invoice[4]) ? @$invoice[4] : 0];
        $yesterday['refund']     = ['icon' => 'fa-file-invoice-dollar text-danger', 'title' => 'Refund Order', 'value' => (@$invoice[6]) ? @$invoice[6] : 0];

        return view('admin.dashboard')->with('blocks', $blocks)->with('total', $total)->with('today', $today)->with('yesterday', $yesterday);
    }

    /**
     * Show Admin Setting
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function setting(Request $request)
    {
        return view('admin.setting');
    }

    /**
     * Show Admin Backup
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function backup(Request $request)
    {
        if ($request->backup == 'yes') {
            Artisan::call("backup:run --disable-notifications");
            return redirect('backup')->with('success', 'New Backup Created!');
        }
        return view('admin.backup');
    }

    /**
     * Show the Search.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        if (strlen($request->q) == 8 && preg_match("/[0-9]{8}/", request()->q)) {
            $invoice = substr(request()->q, 0, -1) - 1000000;
            return redirect('invoice/' . $invoice);
        }
        $q        = "%" . trim($request->q) . "%";
        $customer = User::with('invoices')->where('status', '>', 0)->whereLike(['name', 'mobile', 'email'], $q)->get();
        $invoices = Invoice::with('user')->whereLike(['id', 'tracking', 'referral', 'note', 'system_note'], $q)->get();
        $address  = Address::whereLike(['name', 'mobile', 'email'], $q)->get()->pluck('id')->toArray();
        $shipping = Invoice::with('user')->whereIn('shipping_id', $address)->get();
        $billing  = Invoice::with('user')->whereIn('billing_id', $address)->get();
        $books    = InvoiceMeta::with(['invoice', 'invoice.user'])->where('product', 'LIKE', '%' . trim($request->q) . '%')->where('invoice_id', ">", 0)->get();
        $invoices = $invoices->merge($shipping);
        $invoices = $invoices->merge($billing);
        return view('admin.search', ['customer' => $customer, 'invoices' => $invoices, 'books' => $books]);
    }

    /**
     * Marketing Data
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function marketing(Request $request)
    {
        if ($request->type == 'export') {
            $columns = ['name', 'mobile', 'email', 'gender', 'dob', 'area'];
            $items   = User::where('role', 'a:1:{i:0;s:8:"customer";}')->select($columns)->get();
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=boiferry_users.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $callback = function () use ($items, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($items as $item) {
                    $row = ['name' => $item->name,
                        'mobile'       => $item->mobile,
                        'email'        => $item->email,
                        'gender'       => $item->gender,
                        'dob'          => $item->dob,
                        'area'         => $item->area,
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
        $items = User::where('role', 'a:1:{i:0;s:8:"customer";}')->paginate(50);
        return view('admin.marketing')->with('items', $items);
    }

    /**
     * Show Profile
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function profile(Request $request)
    {
        return view('admin.profile');
    }

    /**
     * Save Profile
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'old_password'          => 'required|current_password:web',
            'password'              => [
                'required',
                'confirmed',
                new isValidPassword(),
            ],
            'password_confirmation' => 'required',
        ]);

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect("setting")->with('success', 'Password Update Successfull.');
    }
}
