<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Success Responge in JSON
     * @param  array/object/string  $data
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function success($data, $code = 200)
    {
        return response(json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE), $code)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Error Responge in JSON
     * @param  array/object/string  $data
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function error($data, $code = 404)
    {
        return response(json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE), $code)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Make DT Table Button
     * @param  [type] $path [description]
     * @param  array  $ex   [description]
     * @return [type]       [description]
     */
    public function dtButton($path, $ex = [], $extra = [])
    {
        $button            = '\'<div align="center" class="actions">';
        $_button['edit']   = '<a href="' . $path . '/\' + full.id + \'/edit" class="btn btn-icon btn-sm btn-info"><i class="fa fa-edit"></i></a>';
        $_button['view']   = '<a href="' . $path . '/\' + full.id + \'" class="btn btn-icon btn-sm btn-info"><i class="fa fa-eye"></i></a>';
        $_button['delete'] = '<a href="javascript:{};" onclick="__delete(\' + full.id + \',' . "\'$path\'" . ');" class="btn btn-icon btn-sm btn-danger"><i class="fa fa-trash"></i></a>';

        if (count($ex) > 0) {
            foreach ($ex as $key => $value) {
                unset($_button[$value]);
            }
        }
        $__extra['print'] = '<a href="' . $path . '/\' + full.id + \'/print" class="btn btn-icon btn-sm btn-info"><i class="fa fa-print"></i></a>';
        $__extra['auth']  = '<a href="' . $path . '/\' + full.id + \'/access" class="btn btn-icon btn-sm btn-warning"><i class="fa fa-fingerprint"></i></a>';
        foreach ($extra as $_extra) {
            if (array_key_exists($_extra, $__extra)) {
                $_button[$_extra] = $__extra[$_extra];
            }
        }

        $button .= implode('&nbsp;&nbsp;', $_button);
        $button .= '</div>\';';
        return $button;
    }

    /**
     * Paginate Collection / Array
     * @param  [type]  $items   [description]
     * @param  integer $perPage [description]
     * @param  [type]  $page    [description]
     * @param  array   $options [description]
     * @return [type]           [description]
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function user()
    {
        $this->user   = Auth::user();
        $this->vendor = $this->user->vendor;
        return $this->user;
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($user, $output = false)
    {
        $token           = Str::random(80);
        $user->api_token = hash('sha256', $token);
        $user->save();

        $_user           = $user->only(['name', 'email', 'mobile']);
        $_user['avatar'] = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email))) . "?&s=50";
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $_user,
        ]);

        /*$subscription = Subscription::where('status', 2)->where('user_id', $user->id)->first();
    if ($subscription && $subscription->expire >= date('Y-m-d')) {
    if ($output) {
    return $token;
    }

    $_user           = $user->only(['name', 'email', 'mobile']);
    $_user['avatar'] = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email))) . "?&s=50";
    return response()->json([
    'access_token' => $token,
    'token_type'   => 'bearer',
    'user'         => $_user,
    ]);

    } else {
    return response()->json(['error' => 'Subscription Expired'], 406);
    }*/
    }

    /**
     * Array to CSV
     * @param  [type] $data        [description]
     * @param  string $delimiter   [description]
     * @param  string $enclosure   [description]
     * @param  string $escape_char [description]
     * @return [type]              [description]
     */
    public function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
    {
        file_put_contents(storage_path('books.csv'), "");
        $f = fopen(storage_path('books.csv'), 'r+');
        foreach ($data as $item) {
            fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
        }
        fclose($f);
        file_put_contents(storage_path('books.csv'), "\xEF\xBB\xBF" . file_get_contents(storage_path('books.csv')));
    }
}
