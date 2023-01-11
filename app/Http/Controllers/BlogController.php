<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class BlogController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [1400, 650];
        $this->image_md     = [646, 300];
        $this->image_sm     = [323, 150];
        $this->image_column = 'image';
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Title'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'SLUG'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.blog_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('blog', ['delete'], ['auth'])];

        if ($request->ajax()) {
            $query    = DB::table('blogs');
            $_columns = [];

            if ($request->status == 'pending') {
                $query = $query->where('status', 0);
            }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "blogs." . $value['data'];
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

        return view('blog.index')
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
        $query = DB::table('blogs');
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
        return view('blog.create');
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
            'name'        => 'required',
            'slug'        => 'required|unique:blogs',
            'description' => 'required',
            'image'       => 'nullabel|image',
            'category'    => 'required',
            'status'      => 'required',
        ]);

        $input = $request->except(['image']);
        if ($request->image) {
            $input = $this->saveImage($request->image, $input);
        }
        $input['seo'] = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : @$input['image']];

        $item = Blog::create($input);

        return redirect('blog')->with('success', 'New Blog Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blog.show')->with('item', $blog);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $blog->password = '';
        return view('blog.edit')->with('item', $blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'name'        => 'required',
            'slug'        => ($blog->slug == $request->slug) ? 'required' : 'required|unique:blogs',
            'description' => 'required',
            'image'       => 'nullable|image',
            'category'    => 'required',
            'status'      => 'required',
        ]);

        $input = $request->except(['image']);
        if ($request->image) {
            $input = $this->saveImage($request->image, $input);
        }
        $input['seo'] = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : @$input['image']];

        $blog->update($input);

        return redirect('blog')->with('success', 'Blog Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        // $blog->delete();
        return ''; // 204 code
    }

}
