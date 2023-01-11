<?php

namespace App\Http\Controllers;

use App\Models\ProductBrand;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class ProductBrandController extends Controller
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
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.productbrand_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('productbrand', ['delete'], ['auth'])];

        if ($request->ajax()) {
            $query    = DB::table('product_brands');
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'product_brands.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "product_brands." . $value['data'];
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

        return view('productbrand.index')
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
        $query = DB::table('product_brands');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'name_bn'], $q);
            }
        }
        $items         = $query->select(['id', 'name as text'])->take(30)->get()->toArray();
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
        return view('productbrand.create');
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

        $item = ProductBrand::create($input);

        return redirect('admin/productbrand')->with('success', 'New ProductBrand Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBrand $productbrand)
    {
        return view('productbrand.show')->with('item', $productbrand);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBrand $productbrand)
    {
        $productbrand->password = '';
        return view('productbrand.edit')->with('item', $productbrand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBrand $productbrand)
    {
        $request->validate([
            'column' => 'required',
            // 'column'  => ($productbrand->column == $request->column) ? 'required' : 'required|unique:product_brands',
        ]);

        $input = $request->except(['']);

        // Use to save image
        // if ($request->image) {
        //     $input = $thos->saveImage($request->image, $input);
        // }

        $productbrand->update($input);

        return redirect('admin/productbrand')->with('success', 'ProductBrand Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBrand $productbrand)
    {
        // $productbrand->delete();
        return ''; // 204 code
    }

}
