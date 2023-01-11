<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class CouponController extends Controller
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
            ['data' => 'code', 'name' => 'code', 'title' => 'Code'],
            ['data' => 'start', 'name' => 'start', 'title' => 'Start Date'],
            ['data' => 'expire', 'name' => 'expire', 'title' => 'Expire Date'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
            ['data' => 'min_shopping', 'name' => 'min_shopping', 'title' => 'Min. Shopping'],
        ];

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.coupon_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('coupon', ['delete', 'view'])];

        if ($request->ajax()) {
            $query    = DB::table('coupons');
            $_columns = ['type'];

            if ($request->status == 'active') {
                $query = $query->where('status', 1);
            }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "coupons." . $value['data'];
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

        return view('coupon.index')
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
        $query = DB::table('coupons');
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
        return view('coupon.create');
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
            'code'        => 'required|unique:coupons',
            'start'       => 'required',
            'expire'      => 'required',
            'amount'      => 'required',
            'coupon_type' => 'required',
            'type'        => 'required',
            'status'      => 'required',
        ]);

        $input           = $request->except(['']);
        $input['start']  = ($input['start']) ? date('Y-m-d', strtotime($input['start'])) : '';
        $input['expire'] = ($input['expire']) ? date('Y-m-d', strtotime($input['expire'])) : '';
        $item            = Coupon::create($input);
        return redirect('coupon')->with('success', 'New Coupon Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return view('coupon.show')->with('item', $coupon);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('coupon.edit')->with('item', $coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'        => ($coupon->code == $request->code) ? 'required' : 'required|unique:coupons',
            'start'       => 'required',
            'expire'      => 'required',
            'amount'      => 'required',
            'coupon_type' => 'required',
            'type'        => 'required',
            'status'      => 'required',
        ]);

        $input           = $request->except(['']);
        $input['start']  = ($input['start']) ? date('Y-m-d', strtotime($input['start'])) : '';
        $input['expire'] = ($input['expire']) ? date('Y-m-d', strtotime($input['expire'])) : '';
        $coupon->update($input);

        return redirect('coupon')->with('success', 'Coupon Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        // $coupon->delete();
        return ''; // 204 code
    }

}
