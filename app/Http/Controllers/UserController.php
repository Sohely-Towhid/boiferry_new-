<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Html\Builder;

class UserController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [600, 600];
        $this->image_md     = [300, 300];
        $this->image_sm     = [150, 150];
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
        $user = Auth::user();

        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'mobile', 'name' => 'mobile', 'title' => 'Mobile'],
            ['data' => 'balance', 'name' => 'balance', 'title' => 'Balance', 'class' => 'text-center'],
            ['data' => 'role', 'name' => 'role', 'title' => 'Role', 'class' => 'text-center'],
        ];

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.user_status(full.status)', 'class' => 'text-center'];
        if (in_array($user->role, ['admin', 'manager'])) {
            $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('user', ['delete'], ['auth'])];
        } else {
            $columns[] = ['defaultContent' => '', 'width' => '115px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('user', ['delete', 'edit'], [])];
        }
        if ($request->ajax()) {
            $query    = DB::table('users');
            $_columns = [];

            if ($request->status == 'pending') {
                $query = $query->where('status', 0);
            }
            if ($request->status == 'banned') {
                $query = $query->where('status', 2);
            }
            if ($request->status == 'seller') {
                $query = $query->where('role', 'LIKE', '%vendor%');
            }

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action'])) {
                    $_columns[] = "users." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))
                ->editColumn('role', function ($data) {
                    return @ucfirst(@unserialize($data->role)[0]);
                })->toJson();
        }

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                        // customize cell html
                        $('td:eq(1)', row).html('<a href=\"/user/' + data.id + '\">' + data.name + '</a>');
                        $('td:eq(3)', row).html('<a href=\"tel://' + data.mobile + '\">' + data.mobile + '</a>');
                        $('td:eq(2)', row).html('<a href=\"mailto://' + data.email + '\">' + data.email + '</a>');
                    }",
            ]);

        $type = 'All';
        if ($request->status == 'pending') {
            $type = "Pending";
        }
        if ($request->status == 'banned') {
            $type = "Banned";
        }
        if ($request->status == 'seller') {
            $type = "Seller";
        }
        return view('user.index')
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
        $query = DB::table('users');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name', 'mobile', 'email'], $q);
            }
        }
        $items         = $query->select(['id', 'name', 'mobile', 'email'])->take(30)->get()->toArray();
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
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['admin', 'manager'])) {
            return abort(403);
        }
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required|mobile|unique:users',
            'balance'  => 'required',
            'role'     => 'required',
            'status'   => 'required',
            'password' => 'required',
        ]);

        $input             = $request->except(['password']);
        $input['password'] = bcrypt($request->password);
        $input['role']     = serialize([$request->role]);

        // Use to save image
        // if ($request->image) {
        //     $input = $this->saveImage($request->image,$input);
        // }

        $item = User::create($input);

        return redirect('user')->with('success', 'New User Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['admin', 'manager'])) {
            return abort(403);
        }
        return view('user.show')->with('item', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['admin', 'manager'])) {
            return abort(403);
        }
        $user->password = '';
        return view('user.edit')->with('item', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['admin', 'manager'])) {
            return abort(403);
        }

        $request->validate([
            'name'    => 'required',
            'mobile'  => ($user->mobile == $request->mobile) ? 'required|mobile' : 'required|mobile|unique:users',
            'email'   => ($user->email == $request->email) ? 'required|email' : 'required|email|unique:users',
            'balance' => 'required',
            'role'    => 'required',
            'status'  => 'required',
        ]);

        $input = $request->except(['password']);
        if ($request->password) {
            $input['password'] = bcrypt($request->password);
        }
        $input['role'] = serialize([$request->role]);

        $user->update($input);

        return redirect('user/' . $user->id . "/edit")->with('success', 'User Details Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user->delete();
        return ''; // 204 code
    }

}
