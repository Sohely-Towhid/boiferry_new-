<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class ProductController extends Controller
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
        $type    = "All";
        $user    = $this->user();
        $layout  = ($user->role == 'vendor') ? 'seller' : 'admin';
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'sku', 'name' => 'sku', 'title' => 'SKU'],
            ['data' => 'rate', 'name' => 'rate', 'title' => 'Rate'],
            ['data' => 'sale', 'name' => 'sale', 'title' => 'Sale'],
            ['data' => 'stock', 'name' => 'stock', 'title' => 'Stock'],
            ['data' => 'sold', 'name' => 'sold', 'title' => 'Sold'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.product_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('product', ['delete'], ['auth'])];

        if ($request->ajax()) {
            $query    = DB::table('products')->where('type', 0);
            $_columns = [];

            if ($request->type == 'pending') {
                $query = $query->where('status', 0);
            }
            if ($request->type == 'active') {
                $query = $query->where('status', 1);
            }
            if ($request->type == 'rejected') {
                $query = $query->where('status', 2);
            }
            if ($request->type == 'stockout') {
                $query = $query->where('stock', 0);
            }

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'products.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "products." . $value['data'];
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

        $type = ucfirst($request->get('type', 'All'));
        return view('product.index')
            ->with('type', $type)
            ->with('layout', $layout)
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
        $query = DB::table('products');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name'], $q);
            }
        }
        $items         = $query->select(['id', 'name'])->take(30)->get()->toArray();
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
        $user   = $this->user();
        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        return view('product.create')->with('layout', $layout);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->user();
        $request->validate([
            'category_id'       => 'required|numeric',
            'brand_id'          => 'required|numeric',
            'name'              => 'required',
            'images'            => 'required',
            'short_description' => 'required|min_word:10',
            'description'       => 'required|min_word:10',
            'sku'               => 'required|unique:products',
            'size.*'            => 'required',
            'color.*'           => 'required',
            'rate.*'            => 'required|numeric',
            'sale.*'            => 'required|numeric|lte:rate.*',
            'stock.*'           => 'required|numeric',
            'point'             => 'nullable|numeric',
        ]);

        $input                      = $request->except(['user_id', 'vendor_id', '_images']);
        $input['user_id']           = $user->id;
        $input['vendor_id']         = $user->vendor_id;
        $input['sold']              = 0;
        $input['in_home']           = 0;
        $input['status']            = 0;
        $input['rating_review']     = ['rating' => 0, 'rating_total' => 0, 'review' => 0, 'review_total' => 0];
        $input['images']            = explode(',', clean($request->images));
        $input['type']              = 0;
        $input['seo']               = '';
        $input['point']             = 0;
        $input['short_description'] = clean($input['short_description']);
        $input['description']       = clean($input['description']);

        $item = [];
        foreach ($request->size as $key => $value) {
            $input['size']  = $request->size[$key];
            $input['color'] = $request->color[$key];
            $input['sale']  = $request->sale[$key];
            $input['rate']  = $request->rate[$key];
            $input['stock'] = $request->stock[$key];
            $input['type']  = ($key == 0) ? 0 : $item[0]->id;
            $item[$key]     = Product::create($input);
        }

        return redirect('product')->with('success', 'new ProductCreated!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('product.show')->with('item', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $user   = $this->user();
        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        $items  = Product::where('vendor_id', $user->vendor_id)
            ->where(function ($query) use ($product) {
                $query->where('id', $product->id)
                    ->orWhere('type', $product->id);
            })->orderBy('id', 'asc')->get();
        return view('product.edit')
            ->with('item', $product)
            ->with('items', $items)
            ->with('layout', $layout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $request->validate([
            'category_id'       => 'required|numeric',
            'brand_id'          => 'required|numeric',
            'name'              => 'required',
            'images'            => 'required',
            'short_description' => 'required|min_word:10',
            'description'       => 'required|min_word:10',
            'sku'               => ($product->sku == $request->sku) ? 'required' : 'required|unique:products',
            'size.*'            => 'required',
            'color.*'           => 'required',
            'rate.*'            => 'required|numeric',
            'sale.*'            => 'required|numeric|lte:rate.*',
            'stock.*'           => 'required|numeric',
            'point'             => 'nullable|numeric',
        ]);

        $input = $request->except(['user_id', 'vendor_id', '_images']);

        foreach ($request->pid as $key => $value) {
            Product::where('id', $value)->update([
                'size'  => $request->size[$key],
                'color' => $request->color[$key],
                'rate'  => $request->rate[$key],
                'sale'  => $request->sale[$key],
                'stock' => $request->stock[$key],
            ]);
        }

        Product::whereIn('id', $request->pid)->update([
            'category_id'       => $request->category_id,
            'brand_id'          => $request->brand_id,
            'name'              => $request->name,
            'images'            => explode(',', clean($request->images)),
            'short_description' => clean($request->short_description),
            'description'       => clean($request->description),
            'sku'               => $request->sku,
        ]);

        if (count($request->size) > count($request->pid)) {
            foreach ($request->size as $key => $value) {
                if ($key > count($request->pid) - 1) {
                    $np        = $product->replicate();
                    $np->size  = $request->size[$key];
                    $np->color = $request->color[$key];
                    $np->sale  = $request->sale[$key];
                    $np->rate  = $request->rate[$key];
                    $np->stock = $request->stock[$key];
                    $np->type  = $product->id;
                    $np->save();
                }
            }
        }

        return redirect('product/' . $product->id . '/edit')->with('success', 'Product Details Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // $product->delete();
        return ''; // 204 code
    }

}
