<?php

namespace App\Http\Controllers;

use App\Models\Page;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class PageController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        // $this->image_lg = [1200, 360];
        // $this->image_md     = [600, 600];
        // $this->image_sm     = [300, 300];
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
        $path    = $image->store('redactor', 'redactor');
        $lg      = Image::make(public_path('assets/images/' . $path))->resize(1200, 630);
        $lg_path = public_path('assets/images/' . $path);
        $lg->save($lg_path, 100);
        return $path;
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Title'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'SLUG'],
        ];

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.page_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('page', ['delete'], ['auth'])];

        if ($request->ajax()) {
            $query    = DB::table('pages');
            $_columns = [];

            if ($request->status == 'pending') {
                $query = $query->where('status', 0);
            }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "pages." . $value['data'];
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

        return view('page.index')
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
        $query = DB::table('pages');
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
        return view('page.create');
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
            'name'          => 'required',
            'slug'          => 'required|unique:pages',
            'description'   => 'required',
            'status'        => 'required',
            'og_image_file' => 'nullable|image',
        ]);

        $input = $request->except(['image']);

        $input['seo'] = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : @$input['image']];
        if ($request->og_image_file) {
            $input['seo']['og_image'] = $this->saveImage($request->og_image_file);
        }

        $item = Page::create($input);

        return redirect('page')->with('success', 'New Page Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return view('page.show')->with('item', $page);
    }

    public function showLegal(Request $request, $slug)
    {
        return '**ok**';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOther($slug = '')
    {
        if (!$slug) {return abort(404);}
        $page = Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('books.page')->with('item', $page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $page->password = '';
        return view('page.edit')->with('item', $page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'name'          => 'required',
            'slug'          => ($page->slug == $request->slug) ? 'required' : 'required|unique:pages',
            'description'   => 'required',
            'status'        => 'required',
            'og_image_file' => 'nullable|image',
        ]);

        $input        = $request->except(['og_image_file']);
        $input['seo'] = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : @$input['image']];
        if ($request->og_image_file) {
            $input['seo']['og_image'] = $this->saveImage($request->og_image_file);
        }
        $page->update($input);

        return redirect('page/' . $page->id . '/edit')->with('success', 'Page Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        // $page->delete();
        return ''; // 204 code
    }

}
