<?php

namespace App\Http\Controllers;

use App\Curl;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\Library;
use Auth;
use Cache;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $items = new Library();
        $items = $items->query()
            ->with(array('book' => function ($query) {
                $query->select('id', 'slug', 'author', 'author_bn', 'images', 'language', 'isbn', 'number_of_page', 'title', 'title_bn');
            }));
        $items = $items->where('user_id', $user->id);
        return $items->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function a2l(Request $request, $slug)
    {
        $book    = Book::where('slug', $slug)->FirstorFail();
        $user    = Auth::user();
        $library = Library::where('user_id', $user->id)->where('book_id', $book->id)->first();
        if (!$library) {
            Library::create(['user_id' => $user->id, 'book_id' => $book->id, 'others' => ['cfi' => '', 'total' => 0, 'current' => 0]]);
        }
        return $this->success('Book Added to Library', 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function r4l(Request $request, $id)
    {
        $user = Auth::user();
        Library::where('user_id', $user->id)->where('book_id', $id)->delete();
        return $this->success('deleted', 204);
    }

    /**
     * Update Book Read
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function apiUpdate(Request $request, $slug)
    {

        $book = Book::where('slug', $slug)->FirstorFail();
        if ($book->vpc == 0) {
            $book->vpc = $book->number_of_page / $request->total;
            $book->save();
        }
        $apc  = 0;
        $apc  = floor($book->vpc * $request->current);
        $apc  = ($apc > 5) ? $apc - 5 : 0;
        $user = Auth::user();

        $own = Ebook::where('book_id', $book->id)->where('user_id', $user->id)->where('status', 1)->first();
        if ($own) {
            $library = Library::where('user_id', $user->id)->where('book_id', $book->id)->first();
            if (!$library) {
                $library = Library::create(['user_id' => $user->id, 'book_id' => $book->id]);
            }
            $library->progress = $request->progress;
            $library->others   = $request->except('progress', 'book');
            $library->save();
            return $this->pageCount($library, $apc);
        }
        return "**ok**";
        // return $this->success($apc, 200);
        // return $this->success('**saved**', 200);
    }

    public function pageCount($library, $apc)
    {
        $c_name    = 'pt_' . $library->id;
        $last_time = Cache::get($c_name . '_time');
        $old_apc   = Cache::get($c_name . '_page', $apc);
        if (!$last_time) {
            Cache::put($c_name . '_time', microtime(true), 60 * 10);
            Cache::put($c_name . '_page', $apc, 60 * 10);
        }
        $now_time = microtime(true);
        $diff     = $now_time - $last_time;
        if ($diff >= 50) {
            $page_diff        = $apc - $old_apc;
            $actual_page_diff = ($page_diff > 2) ? 2 : $page_diff;
            if ($page_diff > 0) {
                $library->apc += $actual_page_diff;
                $library->save();
                Cache::put($c_name . '_time', microtime(true), 60 * 10);
                Cache::put($c_name . '_page', $old_apc + $actual_page_diff, 60 * 10);
            }
            return $this->success(["apc" => $library->apc, 'old_apc' => $old_apc, 'time' => $diff, 'page_diff' => $page_diff], 200);
        } else {
            return $this->success(["apc" => $library->apc, 'time' => $diff], 200);
        }
    }

    /**
     * Ebook But Check
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function apiCheck(Request $request, $id)
    {
        $user = Auth::user();
        $own  = Ebook::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        if ($own->status == 1) {
            return $this->success(['message' => 'eBook Purchase Successfull.']);
        }
        if ($own->status == 2) {
            return $this->success(['message' => 'eBook Purchase Refunded.']);
        }
        if ($own->status == 0) {
            return $this->error(['message' => 'eBook Purchase Failed.'], 406);
        }
    }

    /**
     * API Buy
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function apiBuy(Request $request, $slug)
    {
        $book = Book::where('slug', $slug)->whereNotNull('ebook')->FirstorFail();
        $user = Auth::user();
        $own  = Ebook::where('book_id', $book->id)->where('user_id', $user->id)->where('status', 1)->first();
        if ($own) {
            return $this->error(["message" => "You bought this book!"], 406);
        }
        $ebook = Ebook::updateOrCreate(['user_id' => $user->id, 'book_id' => $book->id], ['user_id' => $user->id, 'book_id' => $book->id, 'status' => 0, 'price' => $book->ebook_sale]);
        $total = $book->ebook_sale;

        $post_data                 = [];
        $post_data['shop_name']    = config('services.ssl.shop_name');
        $post_data['store_id']     = config('services.ssl.store_id');
        $post_data['store_passwd'] = config('services.ssl.store_passwd');
        $post_data['total_amount'] = $total;
        $post_data['currency']     = "BDT";
        $post_data['tran_id']      = $ebook->id . "_" . uniqid();
        $post_data['success_url']  = url('api/ebook/sslcommerz/ipn');
        $post_data['fail_url']     = url('api/ebook/sslcommerz/ipn');
        $post_data['cancel_url']   = url('api/ebook/sslcommerz/ipn');

        # CUSTOMER INFORMATION
        $post_data['cus_name']     = $user->name;
        $post_data['cus_email']    = $user->email;
        $post_data['cus_add1']     = 'Dhaka';
        $post_data['cus_add2']     = '';
        $post_data['cus_city']     = 'Dhaka';
        $post_data['cus_state']    = 'Dhaka';
        $post_data['cus_postcode'] = rand(1000, 1205);
        $post_data['cus_country']  = 'Bangladesh';
        $post_data['cus_phone']    = $user->mobile;
        $post_data['cus_fax']      = "";

        $post_data['ship_name']     = $user->name;
        $post_data['ship_email']    = $user->email;
        $post_data['ship_add1']     = 'Dhaka';
        $post_data['ship_add2']     = '';
        $post_data['ship_city']     = 'Dhaka';
        $post_data['ship_state']    = 'Dhaka';
        $post_data['ship_postcode'] = rand(1000, 1205);
        $post_data['ship_country']  = 'Bangladesh';
        $post_data['ship_phone']    = $user->mobile;
        $post_data['ship_fax']      = "";

        # Product Details
        $post_data['product_name']     = "Boiferry eBook x " . $request->get('order', 1) . " Item";
        $post_data['cart']             = [['product' => $post_data['product_name'], 'amount' => $total]];
        $post_data['product_category'] = "eBook";
        $post_data['product_profile']  = "non-physical-goods";
        $post_data['shipping_method']  = "NO";

        $ch  = new Curl();
        $ssl = $ch->post(config('services.ssl.process'), '', $post_data);
        $ssl = json_decode($ssl, true);
        return response()->json(['ebook' => $ebook->id, 'status' => 'success', 'data' => $ssl['GatewayPageURL'], 'logo' => $ssl['storeLogo']]);
    }
}
