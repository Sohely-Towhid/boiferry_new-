<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Product;
use App\Models\Vendor;
use App\Notifications\SellerMessage;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Yajra\DataTables\Html\Builder;

class VendorController extends Controller
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
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Shop Name'],
            ['data' => 'mobile', 'name' => 'mobile', 'title' => 'Mobile'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'fee', 'name' => 'fee', 'title' => 'Fee', 'class' => 'text-center'],
            // ['data' => 'book', 'name' => 'book', 'title' => 'Sells Book?'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'book', 'name' => 'book', 'title' => 'Sells Book?', 'render' => 'window.sell_books(full.book)', 'class' => 'text-center'];
        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.vendor_status(full.status)', 'class' => 'text-center'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('seller', ['delete'])];

        if ($request->ajax()) {
            $query    = DB::table('vendors');
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'vendors.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "vendors." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        // $('td:eq(1)', row).html('<a href=\"\">' + data.column + '</a>');
                    }",
            ]);

        return view('vendor.index')
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
        $query = DB::table('vendors');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'mobile'], $q);
            }
        }
        $items         = $query->select(['id', 'name', 'mobile'])->take(30)->get()->toArray();
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
        return view('vendor.create');
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
            'user_id' => 'required',
            'slug'    => ($vendor->slug == $request->slug) ? 'nullable' : 'nullable|unique:vendors',
            'mobile'  => 'required|mobile',
            'email'   => 'required|email',
            'fee'     => 'required|numaric',
            'address' => 'required',
            'status'  => 'required',
        ]);

        $input = $request->except(['']);

        $item = Vendor::create($input);

        return redirect('seller')->with('success', 'New Vendor Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($vendor)
    {
        $vendor = Vendor::FindOrFail($vendor);
        return view('vendor.show')->with('item', $vendor);
    }

    public function download(Request $request, $vendor)
    {
        $vendor = Vendor::FindOrFail($vendor);
        return Storage::disk('internal')->download(str_replace("internal/", "", $request->file));
        // return view('vendor.show')->with('item', $vendor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($vendor)
    {
        $vendor = Vendor::FindOrFail($vendor);
        return view('vendor.edit')->with('item', $vendor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vendor)
    {
        $vendor = Vendor::FindOrFail($vendor);
        $request->validate([
            'user_id' => 'required',
            'slug'    => ($vendor->slug == $request->slug) ? 'nullable' : 'nullable|unique:vendors',
            'mobile'  => 'required|mobile',
            'email'   => 'required|email',
            'fee'     => 'required|numeric',
            'address' => 'required',
            'status'  => 'required',
        ]);

        $input                    = $request->except(['']);
        $input['details']['bank'] = $request->bank;
        $old_vendor               = $vendor->status;
        $vendor->update($input);

        // dd([$old_vendor, $vendor]);
        $user = $vendor->user;

        if ($vendor->status == 1 && $old_vendor != 1) {
            $user->status    = 1;
            $user->role      = serialize(['vendor']);
            $user->vendor_id = $vendor->id;
            $user->save();
            $lines[] = 'Your application for being a seller is **accepted**. You can now join our seller center by visiting: ' . route('seller.seller_home') . ' , use your old password and email to sign in.';
            $lines[] = 'After signing in please upload store logo, banner and add new product. Our seller support team will review your product and publish them.';
            $lines[] = 'Please be aware that we do not allow uploading **illegal products**.';
            $link    = [route('seller.seller_home'), 'Seller Center'];
            $user->notify(new SellerMessage($user, 'Welcome to our seller program.', $lines, $link, 'success'));
        }

        if ($vendor->status == 2 && $old_vendor != 2) {
            $lines[] = 'Your application for being a seller is rejected due to "**' . $request->get('reason', 'Lack of Documents') . '**". If you think this is a mistake please call our seller helpline.';
            $lines[] = 'Please check our seller policy for more information.';
            $user->notify(new SellerMessage($user, 'Application Rejected!', $lines, false, 'error'));
        }

        if ($vendor->status == 3 && $old_vendor != 3) {
            $lines[] = 'You are banned from our seller program due to "**seller policy**" violation. If you believe this is a mistake, call our seller support hotline.';
            $lines[] = 'Your products will be no longer available in our marketplace. Any due will be reconciled using our seller policy.';
            $lines[] = 'You no longer have access to the seller center.';
            $user->notify(new SellerMessage($user, 'You are banned from our reseller program.', $lines, false, 'error'));
        }

        if ($vendor->status == 3) {
            Product::where('vendor_id', $vendor->id)->update(['status' => 2]);
            Book::where('vendor_id', $vendor->id)->update(['status' => 2]);
        }

        return redirect('seller/' . $vendor->id . '/edit')->with('success', 'Vendor Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        // $vendor->delete();
        return ''; // 204 code
    }

}
