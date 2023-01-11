<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use Artisan;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class PayoutController extends Controller
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
        if ($request->sync == 'yes') {
            Artisan::call('make:payout');
            return redirect('payout')->with('success', 'Payout Sync Done');
        }
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Date Time', 'class' => 'text-center'],
            ['data' => 'seller_name', 'name' => 'seller_name', 'title' => 'Seller'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount', 'class' => 'text-center'],
            ['data' => 'pg_fee', 'name' => 'pg_fee', 'title' => 'PG Fee', 'class' => 'text-center'],
            ['data' => 'fee', 'name' => 'fee', 'title' => 'WB Fee', 'class' => 'text-center'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Payable', 'render' => 'Number(full.amount) - Number(full.fee) - Number(full.pg_fee)', 'class' => 'text-center'],
            ['data' => 'method', 'name' => 'method', 'title' => 'Method', 'class' => 'text-center'],
            ['data' => 'details', 'name' => 'details', 'title' => 'Details'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.payout_status(full.status)', 'class' => 'text-center'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('payout', ['delete', 'edit'], ['print'])];

        if ($request->ajax()) {
            $query    = DB::table('payouts');
            $_columns = ['vendors.name as seller_name', 'payouts.vendor_id'];

            if ($request->status == 'pending') {
                $query->where('payouts.status', 0);
            }
            if ($request->status == 'unpaid') {
                $query->where('payouts.status', 1);
            }
            if ($request->status == 'paid') {
                $query->where('payouts.status', 2);
            }
            // Write Extra Query if Needed
            // Join Query
            $query = $query->join('vendors', 'payouts.vendor_id', '=', 'vendors.id');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'seller_name'])) {
                    $_columns[] = "payouts." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        $('td:eq(2)', row).html('<a href=\"seller\/'+ data.vendor_id +'\">' + data.seller_name + '</a>');
                    }",
            ]);

        return view('payout.index')
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
        $query = DB::table('payouts');
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
        return view('payout.create');
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

        $item = Payout::create($input);

        return redirect('admin/payout')->with('success', 'New Payout Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payout $payout)
    {
        return view('payout.show')->with('item', $payout);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Payout $payout)
    {
        $payout->password = '';
        return view('payout.edit')->with('item', $payout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payout $payout)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'accounts'])) {
            return abort(403);
        }
        $request->validate([
            'status'  => 'required',
            'details' => 'required',
        ]);

        $input            = $request->except(['user_id']);
        $input['user_id'] = $user->id;
        if ($payout->status == 2) {
            return redirect('payout/' . $payout->id)->with('danger', 'Update is not possible.');
        }

        $payout->update($input);

        return redirect('payout/' . $payout->id)->with('success', 'Payout Details Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payout $payout)
    {
        // $payout->delete();
        return ''; // 204 code
    }

}
