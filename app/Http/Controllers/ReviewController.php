<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class ReviewController extends Controller
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'star', 'name' => 'star', 'title' => 'Star'],
            ['data' => 'message', 'name' => 'message', 'title' => 'Message'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.review_status(full.status)'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('review', ['delete', 'view'])];

        if ($request->ajax()) {
            $query    = DB::table('reviews')->where('status', '>', 0);
            $_columns = [];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            // $query = $query->join('join_table', 'reviews.column_name', '=', 'join_table.column_name');
            // }
            if ($request->status == 'pending') {
                $query = $query->where('status', 1);
            }
            if ($request->status == 'published') {
                $query = $query->where('status', 2);
            }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "reviews." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $type = ucfirst($request->get('status', 'All'));

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        var str = data.message;
                        console.log(str.length);
                        if(str.length > 100){
                            console.log(str.substr(0, 100-1));
                            $('td:eq(3)',row).html(str.substr(0, 100-1) + '&hellip;');
                        }else{
                            $('td:eq(3)',row).html(str);
                        }
                        // $('td:eq(1)', row).html('<a href=\"\">' + data.column + '</a>');
                    }",
            ]);

        return view('review.index')
            ->with('type', $type)
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
        $query = DB::table('reviews');
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
        return view('review.create');
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
            'name'    => 'required',
            'star'    => 'required',
            'status'  => 'required',
            'message' => 'required',
        ]);

        $input = $request->except(['']);

        $item = Review::create($input);
        $this->syncStar($input);

        return redirect('review')->with('success', 'New Review Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        return view('review.show')->with('item', $review);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        $review->password = '';
        return view('review.edit')->with('item', $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'name'    => 'required',
            'star'    => 'required',
            'status'  => 'required',
            'message' => 'required',
        ]);

        $input = $request->except(['']);
        $review->update($input);
        $this->syncStar($review);
        return redirect('review')->with('success', 'Review Updated!');
    }

    /**
     * Sync Review
     * @param  [type] $review [description]
     * @return [type]         [description]
     */
    public function syncStar($review)
    {
        $item = Book::find($review->book_id);
        $data = Review::where('id', $review->id)->where('status', 2)->select([
            DB::RAW('SUM(star) as total_star'),
            DB::RAW('COUNT(id) as total_review'),
            DB::RAW('SUM(case when star = 5 then 1 else 0 end) as star_5'),
            DB::RAW('SUM(case when star = 4 then 1 else 0 end) as star_4'),
            DB::RAW('SUM(case when star = 3 then 1 else 0 end) as star_3'),
            DB::RAW('SUM(case when star = 2 then 1 else 0 end) as star_2'),
            DB::RAW('SUM(case when star = 1 then 1 else 0 end) as star_1'),
        ])->first();
        if ($data->total_star > 0 && $data->total_review > 0) {
            $avg   = $data->total_star / $data->total_review;
            $_data = [
                'rating'       => $avg,
                'rating_total' => $data->total_review,
                'rating_list'  => [
                    'star_5' => $data->star_5,
                    'star_4' => $data->star_4,
                    'star_3' => $data->star_3,
                    'star_2' => $data->star_2,
                    'star_1' => $data->star_1,
                ],
            ];
            $item->rating_review = $_data;
            $item->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        // $review->delete();
        return ''; // 204 code
    }

}
