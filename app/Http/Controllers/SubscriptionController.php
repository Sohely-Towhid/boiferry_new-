<?php

namespace App\Http\Controllers;

use App\Curl;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class SubscriptionController extends Controller
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
            ['data' => 'name', 'name' => 'user_id', 'title' => 'User'],
            ['data' => 'validity_days', 'name' => 'validity_days', 'title' => 'Days', 'class' => 'text-center'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount', 'class' => 'text-center'],
            ['data' => 'expire', 'name' => 'expire', 'title' => 'Expire', 'class' => 'text-center'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Subscription at', 'class' => 'text-center'],
        ];

        // Conditional Column
        // if () {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        // }

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.subscription_status(full.status)', 'class' => 'text-center'];
        $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('subscription', ['delete', 'view'])];

        if ($request->ajax()) {
            $query = DB::table('subscriptions');
            if ($request->status == 'active') {
                $query = $query->where('subscriptions.status', 2);
            }
            if ($request->status == 'expired') {
                $query = $query->where('subscriptions.status', 3);
            }
            $_columns = ['users.name as name', 'subscriptions.user_id'];

            // if () {
            // Write Extra Query if Needed
            // Join Query
            $query = $query->join('users', 'subscriptions.user_id', '=', 'users.id');
            // }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'name'])) {
                    $_columns[] = "subscriptions." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        $('td:eq(1)', row).html('<a href=\"/user/' + data.user_id + '\">' + data.name + '</a>');
                    }",
            ]);

        return view('subscription.index')
            ->with('type', $request->status)
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
        $query = DB::table('subscriptions');
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
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            return abort(403);
        }
        return view('subscription.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            return abort(403);
        }
        $request->validate([
            'user_id'       => 'required',
            'amount'        => 'required',
            'validity_days' => 'required',
            'payment'       => 'required',
            'expire'        => 'required',
            'status'        => 'required',
        ]);

        $input = $request->except(['']);

        // Use to save image
        // if ($request->image) {
        //     $input = $this->saveImage($request->image,$input);
        // }

        $item = Subscription::create($input);

        return redirect('subscription')->with('success', 'New Subscription Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        return view('subscription.show')->with('item', $subscription);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            return abort(403);
        }
        return view('subscription.edit')->with('item', $subscription);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            return abort(403);
        }

        $request->validate([
            'user_id'       => 'required',
            'amount'        => 'required',
            'validity_days' => 'required',
            'payment'       => 'required',
            'expire'        => 'required',
            'status'        => 'required',
        ]);

        $input = $request->except(['']);

        $subscription->update($input);

        return redirect('subscription')->with('success', 'Subscription Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        // $subscription->delete();
        return ''; // 204 code
    }

    /**
     * SSL IPN
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subscriptionSSL(Request $request)
    {
        if ($request->tran_id) {
            $trns = explode("_", $request->tran_id);
            if (count($trns) != 3) {return abort(404);}
            $user  = User::findOrFail($trns[0]);
            $month = Setting::getValue('subscription_' . $trns[1]);
            if (!$month) {return abort(404);}

            $ch                   = new Curl();
            $post['val_id']       = $request->val_id;
            $post['store_id']     = config('services.ssl.store_id');
            $post['store_passwd'] = config('services.ssl.store_passwd');
            $post['format']       = 'json';
            $data                 = json_decode($ch->get(config('services.ssl.check') . '?' . http_build_query($post)));

            if ($data) {
                if (in_array($data->status, ['VALID', 'VALIDATED']) && $data->amount >= $month) {

                    $system_note[] = 'sslcommerz#' . $request->val_id;
                    $system_note[] = 'sslcommerz_trns#' . $request->tran_id;
                    $system_note[] = 'sslcommerz_card#' . $request->card_issuer . " | " . $request->card_brand . '|' . $request->card_issuer_country;

                    $subscription = Subscription::create(['user_id' => $user->id, 'validity_days' => $trns[1] * 30, 'amount' => $month, 'payment' => json_encode($system_note), 'status' => 1]);
                    $old_sub      = Subscription::where('user_id', $user->id)->where('status', 2)->orderBy('expire', 'desc')->first();
                    if ($old_sub) {
                        $subscription->expire = date('Y-m-d', strtotime($old_sub->expire . " + " . $subscription->validity_days . " days"));
                        $subscription->status = 2;
                        $subscription->save();
                        $old_sub->status = 3;
                        $old_sub->save();
                    } else {
                        $subscription->expire = date('Y-m-d', strtotime("+ " . $subscription->validity_days . " days"));
                        $subscription->status = 2;
                        $subscription->save();
                    }
                    return redirect('my-account/subscription?payment=success');
                }
            } else {
                return redirect('my-account/subscription?payment=failed&msg=Payment Processing Failed, Please Try Again!');
            }
        }
        return redirect('my-account/subscription?payment=failed&msg=Payment Processing Failed, Please Try Again!');
    }
}
