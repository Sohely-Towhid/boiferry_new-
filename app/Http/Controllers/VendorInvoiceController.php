<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceMeta;
use App\Models\VendorInvoice;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class VendorInvoiceController extends Controller
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
    public function index(Request $request)
    {
        $user   = Auth::user();
        $status = ['-', 'System Pending', 'pending', 'shipped', 'completed', 'cancelled', 'refunded', 'packed'];
        $items  = VendorInvoice::with(['invoice', 'metas'])->where('vendor_id', $user->vendor_id)->where('status', '>', 1);

        if ($request->status) {
            $items = $items->where('status', array_search($request->status, $status));
        }

        return view('vendorinvoice.index')
            ->with('type', ucfirst($request->status))
            ->with('items', $items->paginate(15));
    }

    /**
     * Display a listing of the resource in select2 formate (no pagination).
     * Special Search Feature ID:123 will return one item from given id
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $query = DB::table('vendor_invoices');
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
        return view('vendorinvoice.create');
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

        $item = VendorInvoice::create($input);

        return redirect('admin/vendorinvoice')->with('success', 'New VendorInvoice Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user    = Auth::user();
        $item    = VendorInvoice::with(['invoice', 'metas'])->where('vendor_id', $user->vendor_id)->where('id', $id)->FirstOrFail();
        $invoice = Invoice::where('id', $item->invoice->id)->FirstOrFail();
        if ($request->print == 'yes') {
            $item->print = 1;
            $item->save();
            return $this->success('**ok**');
        }
        if ($request->packed == 'yes' && $item->status == 2) {
            $item->status = 7;
            $item->save();
            InvoiceMeta::where('invoice_id', $item->invoice_id)->where('vendor_id', $item->vendor_id)->update(['status' => 7]);
            $items = InvoiceMeta::where('invoice_id', $item->invoice_id)->select([DB::RAW('SUM(case when status = 7 then 1 else 0 end) as inv_com, COUNT(id) as inv_tot')])->first();

            $sp              = 100 / $items->inv_tot;
            $np              = $sp * $items->inv_com;
            $invoice->packed = $np;
            if ($np == 100) {
                $invoice->status = 7;
            }
            $invoice->save();
            return redirect('invoice/' . $item->id)->with('success', 'Invoice Marked As Packed!');
        }
        return view('vendorinvoice.show')->with('item', $item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorInvoice $vendorinvoice)
    {
        $vendorinvoice->password = '';
        return view('vendorinvoice.edit')->with('item', $vendorinvoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorInvoice $vendorinvoice)
    {
        $request->validate([
            'column' => 'required',
            // 'column'  => ($vendorinvoice->column == $request->column) ? 'required' : 'required|unique:vendor_invoices',
        ]);

        $input = $request->except(['']);

        // Use to save image
        // if ($request->image) {
        //     $input = $thos->saveImage($request->image, $input);
        // }

        $vendorinvoice->update($input);

        return redirect('admin/vendorinvoice')->with('success', 'VendorInvoice Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorInvoice $vendorinvoice)
    {
        // $vendorinvoice->delete();
        return ''; // 204 code
    }

}
