<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class ProductCategoryController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [1200, 1200];
        $this->image_md     = [600, 600];
        $this->image_sm     = [300, 300];
        $this->image_column = 'photo';
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

        $input[$this->image_column] = $path;
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
            ['data' => 'parent', 'name' => 'parent', 'title' => 'Parent', 'render' => '(full.parent==0)? "Self": window._parents[full.parent]'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'name_bn', 'name' => 'name_bn', 'title' => 'Name Bangla'],
        ];

        $columns[] = ['defaultContent' => '', 'width' => '80px', 'class' => 'text-center', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('category', ['delete', 'view'])];

        if ($request->ajax()) {
            $query    = DB::table('product_categories');
            $_columns = [];

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'skip'])) {
                    $_columns[] = "product_categories." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'orderFixed'      => [1, 'asc'],
                'rowGroup'        => [
                    'startRender' => "function ( rows, group ) {return (group==0)? 'Main Parent' +' ('+rows.count()+')' : window._parents[group] +' ('+rows.count()+')';}",
                    'endRender'   => null,
                    'dataSrc'     => 'parent',
                ],
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                    // customize cell html
                    // $('td:eq(1)', row).html('<a href=\"\">' + data.column + '</a>');
                }",
            ]);

        return view('productcategory.index')
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
        $user    = $this->user();
        $parents = DB::table('product_categories')->where('parent', 0)->get()->pluck('name_bn', 'id')->toArray();
        $query   = DB::table('product_categories');
        if ($user->role == 'vendor') {
            $query = $query->whereIn('id', $this->vendor->category);
        }
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'name_bn'], $q);
            }
        }
        $items = $query->select(['id', 'name_bn as text', 'name_bn', 'parent'])->take(30)->get()->toArray();
        array_push($items, ['id' => '*', 'text' => 'All', 'name_bn' => 'All']);
        foreach ($items as $key => $value) {
            if (@$value->parent > 0) {
                $items[$key]->text = $parents[$value->parent] . " > " . $value->text;
            }
        }
        $re['role']    = $user->role;
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
        return view('productcategory.create');
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
            'slug'    => 'required|unique:product_categories',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['']);

        // Use to save image
        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        $item = ProductCategory::create($input);

        return redirect('product/category')->with('success', 'New Product Category Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productcategory = ProductCategory::findOrFail($id);
        return view('productcategory.show')->with('item', $productcategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productcategory = ProductCategory::findOrFail($id);
        return view('productcategory.edit')->with('item', $productcategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productcategory = ProductCategory::findOrFail($id);
        $request->validate([
            'slug'    => ($productcategory->slug == $request->slug) ? 'required' : 'required|unique:product_categories',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['']);

        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        $productcategory->update($input);

        return redirect('product/category')->with('success', 'Product Category Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productcategory)
    {
        // $productcategory->delete();
        return ''; // 204 code
    }

}
