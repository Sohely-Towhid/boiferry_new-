<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\SalesMatric;
use Cache;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class AuthorController extends Controller
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
        $ext  = $image->extension();

        $lg      = Image::make(public_path('assets/images/' . $path))->resize($this->image_lg[0], $this->image_lg[1]);
        $lg_path = public_path('assets/images/' . str_replace(["redactor/", $ext], ["redactor/lg_", 'webp'], $path));
        $lg->encode('webp', 100)->save($lg_path, 100);

        $md      = Image::make(public_path('assets/images/' . $path))->resize($this->image_md[0], $this->image_md[1]);
        $md_path = public_path('assets/images/' . str_replace(["redactor/", $ext], ["redactor/md_", 'webp'], $path));
        $md->encode('webp', 100)->save($md_path, 100);

        $sm      = Image::make(public_path('assets/images/' . $path))->resize($this->image_sm[0], $this->image_sm[1]);
        $sm_path = public_path('assets/images/' . str_replace(["redactor/", $ext], ["redactor/sm_", 'webp'], $path));
        $sm->encode('webp', 30)->save($sm_path, 30);

        $input[$this->image_column] = str_replace($ext, 'webp', $path);

        @copy($lg_path, str_replace('lg_', '', $lg_path));
        if (!preg_match('/webp$/', $path)) {
            @unlink(public_path('assets/images/' . $path));
        }
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
            ['data' => 'select_id', 'name' => 'select_id', 'title' => '', 'orderable' => false, 'searchable' => false],
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'SLUG'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'name_bn', 'name' => 'name_bn', 'title' => 'Name in Bangla'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        // $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.author_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '120px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('author', [], [])];

        if ($request->ajax()) {
            $query    = DB::table('authors');
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'authors.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'select_id'])) {
                    $_columns[] = "authors." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))
                ->addColumn('select_id', static function ($row) {
                    return '<input type="checkbox" class="a_id" name="a_id[]" value="' . $row->id . '"/>';
                })
                ->rawColumns(['select_id'])
                ->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'order'           => [1, 'asc'],
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        // $('td:eq(1)', row).html('<a href=\"\">' + data.column + '</a>');
                    }",
            ]);

        return view('author.index')
            ->with('html', $html);
    }

    public function apiIndex(Request $request)
    {
        return Author::select(['name', 'name_bn', 'slug', 'photo'])->paginate(48);
    }

    /**
     * Display a listing of the resource in select2 formate (no pagination).
     * Special Search Feature ID:123 will return one item from given id
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $query = DB::table('authors');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/ex:(.*)/", $request->q, $m)) {
                $query = $query->where('name', $m[1]);
            } elseif (preg_match("/id:(.*)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'name_bn'], $q);
            }
        }
        if ($request->id == 'yes') {
            $items = $query->select(['id', 'name', 'name_bn', 'name as text'])->take(30)->get()->toArray();
        } else {
            $items = $query->select(['name as id', 'name', 'name_bn', 'name as text'])->take(30)->get()->toArray();
        }

        if ($request->has('q') && !preg_match("/ex:|id:/", request()->q)) {
            $new           = [['id' => request()->q, 'name' => request()->q, 'name_bn' => request()->q]];
            $re['results'] = array_merge($new, $items);
        } elseif (preg_match("/ex:|id:/", request()->q)) {
            if (@$items[0]) {
                @$items[0]->autoselect = true;
            }
            $re['results'] = $items;
        } else {
            $re['results'] = $items;
        }
        return $re;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('author.create');
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
            'slug'    => 'required|unique:authors',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['photo']);

        // Use to save image
        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        $item = Author::create($input);
        Cache::forget('book_authors');

        return redirect('author')->with('success', 'New Author Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return redirect('book?author=' . $author->id);
        return view('author.show')->with('item', $author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        $author->password = '';
        return view('author.edit')->with('item', $author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'slug'    => ($author->slug == $request->slug) ? 'required' : 'required|unique:authors',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['photo']);

        // Use to save image
        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        // Use to save image
        // if ($request->image) {
        //     $input = $thos->saveImage($request->image, $input);
        // }

        $author->update($input);
        Cache::forget('book_authors');

        return redirect('author')->with('success', 'Author Details Updated!');
    }

    /**
     * Author Merge
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function authorMerge(Request $request)
    {
        $request->validate([
            'author_id' => 'required',
            'ids'       => 'required',
        ]);

        $ids = explode(',', $request->ids);
        if (($key = array_search($request->author_id, $ids)) !== false) {
            unset($ids[$key]);
        }

        $author = Author::findOrFail($request->author_id);
        Book::whereIn('author_id', $ids)->update(['author_id' => $author->id]);
        SalesMatric::whereIn('author_id', $ids)->update(['author_id' => $author->id]);
        Author::whereIn('id', $ids)->delete();

        return redirect('author')->with('success', 'Author Merge Successfull!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        if (Book::where('author_id', $author->id)->first()) {
            return $this->error('can not delete this entry', 426);
        } else {
            $author->delete();
            return $this->error('deleted', 204);
        }
    }

}
