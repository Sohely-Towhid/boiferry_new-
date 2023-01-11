<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class PublicationController extends Controller
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
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'SLUG'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'name_bn', 'name' => 'name_bn', 'title' => 'Name in Bangla'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        // $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.publication_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('publication', ['delete', 'view'], [])];

        if ($request->ajax()) {
            $query    = DB::table('publications');
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'publications.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "publications." . $value['data'];
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

        return view('publication.index')
            ->with('html', $html);
    }

    public function apiIndex(Request $request)
    {
        $items = new Publication();
        if (!empty($request->q)) {
            $items = $items->whereLike(['name', 'name_bn', 'slug'], $request->q);
        }
        return $items->select(['name', 'name_bn', 'slug'])->paginate(48);
    }

    /**
     * Display a listing of the resource in select2 formate (no pagination).
     * Special Search Feature ID:123 will return one item from given id
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $query = DB::table('publications');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'name_bn', 'slug'], $q);
            }
        }
        $items         = $query->select(['id', 'name', 'name_bn as text'])->take(30)->get()->toArray();
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
        return view('publication.create');
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
            'slug'    => 'required|unique:publications',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['photo']);

        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        $item = Publication::create($input);

        return redirect('publication')->with('success', 'New Publication Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        return view('publication.show')->with('item', $publication);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Publication $publication)
    {
        $publication->password = '';
        return view('publication.edit')->with('item', $publication);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publication $publication)
    {
        $request->validate([
            'slug'    => ($publication->slug == $request->slug) ? 'required' : 'required|unique:publications',
            'name'    => 'required',
            'name_bn' => 'required',
            'photo'   => 'nullable|image',
        ]);

        $input = $request->except(['photo']);

        if ($request->photo) {
            $input = $this->saveImage($request->photo, $input);
        }

        $publication->update($input);

        return redirect('publication')->with('success', 'Publication Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        // $publication->delete();
        return ''; // 204 code
    }

}
