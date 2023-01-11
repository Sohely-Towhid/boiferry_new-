<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class SliderController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [800, 420];
        $this->image_md     = [400, 210];
        $this->image_sm     = [200, 105];
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
        $ext      = $image->extension();
        $path     = $image->store('redactor', 'redactor');
        $main     = Image::make(public_path('assets/images/' . $path))->resize(1200, 300)->encode('webp', 100);
        $new_path = public_path("assets/images/" . preg_replace('/(.*\.)([a-z]+)$/', "\$1webp", $path));
        $main->save($new_path, 100);
        $input[$this->image_column] = preg_replace('/(.*\.)(' . $ext . ')$/', "\$1webp", $path);

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
            ['data' => 'type', 'name' => 'type', 'title' => 'Site'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.slider_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('slider', ['show'], [])];

        if ($request->ajax()) {
            $query    = DB::table('sliders');
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'sliders.column_name', '=', 'join_table.column_name');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "sliders." . $value['data'];
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

        return view('slider.index')
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
        $query = DB::table('sliders');
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
        return view('slider.create');
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
            'type'   => 'required',
            'status' => 'required',
            'image'  => 'required|image',
        ]);

        $input = $request->except(['image']);

        if ($request->image) {
            $input = $this->saveImage($request->image, $input);
        }

        $item = Slider::create($input);

        return redirect('slider')->with('success', 'New Slider Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return view('slider.show')->with('item', $slider);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slider)
    {
        $slider           = Slider::findorfail($slider);
        $slider->password = '';
        return view('slider.edit')->with('item', $slider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'type'   => 'required',
            'status' => 'required',
            'image'  => 'nullable|image',
        ]);

        $input = $request->except(['image']);

        if ($request->image) {
            $input = $this->saveImage($request->image, $input);
        }

        $slider->update($input);

        return redirect('slider')->with('success', 'Slider Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();
        return $this->success('', 204); // 204 code
    }

}
