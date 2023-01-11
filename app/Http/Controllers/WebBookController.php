<?php

namespace App\Http\Controllers;

use App;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\InvoiceMeta;
use App\Models\Page;
use App\Models\Publication;
use App\Models\Review;
use App\Models\SearchHistory;
use App\Models\Setting;
use Auth;
use Cache;
use DB;
use Illuminate\Http\Request;
use Mail;

class WebBookController extends Controller
{
    /**
     * Books Home
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        // 800x420

        // $invoice     = Invoice::whereIn('status', [2, 3, 4, 7])->orderBy('id', 'desc')->take(40)->get()->pluck('id')->toArray();
        // $latest_sold = InvoiceMeta::whereIn('id', $invoice)->groupBy('book_id')->take(15)->get()->pluck('book_id')->toArray();

        /*$latest_sold = Cache::remember('latest_sold', 60 * 5, function () {
        $invoice = Invoice::whereIn('status', [2, 3, 4, 7])->orderBy('id', 'desc')->take(40)->get()->pluck('id')->toArray();
        return InvoiceMeta::whereIn('id', $invoice)->groupBy('book_id')->take(15)->get()->pluck('book_id')->toArray();
        });*/

        /*$best_sell = Cache::remember('best_sell', 0, function () {
        $best_sell = DB::table('sales_matrics')
        ->whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->groupBy('book_id')->select(['book_id', DB::RAW('COUNT(book_id) as total')])->orderBy('total', 'desc')->take(15)->get()->pluck('book_id')->toArray();
        return $best_sell;
        });*/

        // Best Selling Author
        /*$best_author = Cache::remember('best_author', 60 * 5, function () {
        return DB::table('sales_matrics')->whereYear('created_at', date('Y'))->groupBy('author_id')->select(['author_id', DB::RAW('COUNT(author_id) as total')])->orderBy('total', 'desc')->take(15)->get()->pluck('author_id')->toArray();
        });*/

        /*$latest_sold      = Book::whereIn('status', [1,3])->whereIn('id', $latest_sold)->take(15)->get();
        $best_seller_year = Book::whereIn('status', [1,3])->whereIn('id', $best_sell)
        ->orderByRaw('FIELD(id, ' . implode(',', $best_sell) . ')')
        ->take(15)->get();*/

        /*$authors = Author::whereIn('id', $best_author)
        ->orderByRaw('FIELD(id, ' . implode(',', $best_author) . ')')
        ->take(15)->get();*/

        //  'latest_sold', 'best_seller_year', 'authors'
        $user       = Auth::user();
        $uid        = ($user) ? "_" . $user->id : '';
        $cachedData = Cache::remember('home_page' . $uid, 60 * 5, function () {
            $slides = Cache::remember('slides', 60 * 5, function () {
                $slides = DB::table('sliders')->where('type', 'book')->where('status', 1)->get();
                return json_decode(json_encode($slides));
            });
            return view('books.index', compact('slides'))->render();
        });
        return $cachedData;
        // return view('books.index', compact('slides'));
    }

    /**
     * Ajax Search
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxSearch(Request $request)
    {
        $items = Book::search($request->get('q'))->take(10)->get()->load('publication');
        foreach ($items as $key => $item) {
            $items[$key] = ['id' => $item->id, 'slug' => $item->slug, 'image' => showImage(@$item->images[0], 'xs'), 'title' => $item->title_bn, 'author' => $item->author_bn, 'publications' => @$item->publication->name_bn, 'stock_color' => ($item->stock) ? 'success' : 'danger', 'stock' => ($item->stock) ? 'স্টকে আছে' : 'স্টকে নেই', 'rate' => $item->rate, 'sale' => $item->sale];
        }
        if (count($items) == 0) {
            SearchHistory::create(['search_term' => $request->get('q')]);
        }
        return response()->json($items, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import From Rokomari
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function search(Request $request)
    {
        $items = Book::search($request->get('q'))->paginate(5 * 8);
        if (count($items) == 0) {
            SearchHistory::create(['search_term' => $request->get('q')]);
        }
        return view('books.archive')->with('other', true)->with('items', $items)->with('title', "Search");
    }

    /**
     * Single Book
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bookSingle(Request $request, $slug)
    {

        $item  = Book::with(['_author', 'category', 'publication'])->whereIn('status', [1, 3])->where('slug', $slug)->FirstorFail();
        $ids   = session('recent_views', []);
        $ids[] = $item->id;
        $ids   = array_unique($ids);
        session(['recent_views' => $ids]);
        $ids     = array_diff($ids, [$item->id]);
        $ids_md5 = md5(json_encode($ids));

        $items = Cache::remember('book_' . $item->id . "_" . $ids_md5, 60 * 5, function () use ($item, $ids) {
            $items = Book::whereIn('id', $ids)->whereIn('status', [1, 3])->inRandomOrder()->take(12)->get();
            $total = count($items);
            if ($total < 12) {
                $extra = Book::where('category_id', $item->category_id)->whereIn('status', [1, 3])->inRandomOrder()->take(12 - $total)->get();
                $items = $items->merge($extra);
                $total = count($items);
            }
            if ($total < 12) {
                $extra = Book::whereIn('status', [1, 3])->inRandomOrder()->take(12 - $total)->get();
                $items = $items->merge($extra);
            }
            return $items;
        });

        $bt = Cache::remember('book_bt_' . $item->id, 60 * 5, function () use ($item) {
            $data = InvoiceMeta::where('book_id', $item->id)->take(30)->get()->pluck('invoice_id')->toArray();
            $data = InvoiceMeta::whereIn('invoice_id', $data)->where('book_id', '!=', $item->id)->groupBy('book_id')->get()->pluck('book_id');
            $bt   = Book::whereIn('id', $data)->inRandomOrder()->take(8)->get();
            return $bt;
        });
        // return $this->success([$item, $items, $bt]);
        return view('books.single')->with('item', $item)->with('items', $items)->with('bt', $bt);
    }

    /**
     * Single Book
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bookSinglePost(Request $request, $slug)
    {
        $user = Auth::user();
        $item = Book::with(['_author', 'category', 'publication'])->whereIn('status', [1, 3])->where('slug', $slug)->FirstorFail();
        if ($request->report) {
            Mail::raw(clean($request->report), function ($message) use ($item) {
                $message->to('support@boiferry.com')
                    ->subject('Info Report: ' . url('book/' . $item->slug));
            });
            return redirect('book/' . $item->slug)->with('success', 'Thank you for the report, we will fix it soon.');
        }

        $review = Review::where('id', $request->review_id)->where('status', 0)->first();
        if ($review) {
            $review->verified = 1;
        } else {
            $review           = new Review();
            $review->verified = 0;
            $review->book_id  = $item->id;
            $review->user_id  = $user->id;
            $review->name     = $user->name;
        }
        $review->star    = $request->stars;
        $review->message = $request->review_message;
        $review->status  = 1;
        $review->save();

        // {"rating":3,"rating_total":454,"review":0,"review_total":0,"rating_list":{"star_5":68,"star_4":25,"star_3":88,"star_2":99,"star_1":89}}

        $data = Review::where('id', $item->id)->where('status', 2)->select([
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

        return redirect('book/' . $item->slug)->with('success', 'Thank you for the review, we will publish it soon.');
    }

    /**
     * Show Author list
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function authors(Request $request)
    {
        $items = new Author();
        if ($request->search) {
            $items = $items->whereLike(['name', 'name_bn', 'slug'], $request->search);
        }
        return view('books.archives')->with('authors', true)
            ->with('items', $items->paginate(6 * 8))->with('title', 'সকল লেখক');
    }

    /**
     * Show Author
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function author(Request $request, $slug)
    {
        if (preg_match('/^[0-9]+$/', $slug)) {
            $author = Author::where('id', $slug)->FirstorFail();
            return redirect('author/' . $author->slug);
        } else {
            $author = Author::where('slug', $slug)->FirstorFail();
        }
        $items = Book::whereIn('status', [1, 3])->where('author_id', $author->id)->orWhere('others', 'LIKE', '%' . $author->slug . "%");
        if ($request->year) {$items = $items->whereYear('published_at', $request->year);}
        if ($request->month) {$items = $items->whereMonth('published_at', $request->month);}
        if ($request->rating) {$items = $items->whereLike('rating_review', '"rating":' . $request->rating . ",%");}
        return view('books.archive')->with('author', $author)->with('items', $items->paginate(6 * 8))->with('title', $author->name_bn . ' এর বই সমূহ');
    }

    /**
     * Show Category list
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function categories(Request $request)
    {
        $items = new Category();
        if ($request->ids) {
            $ids   = explode(",", $request->ids);
            $items = $items->whereIn('id', $ids);
        }
        if ($request->search) {
            $items = $items->whereLike(['name', 'name_bn'], $request->search);
        }
        return view('books.archives')->with('categories', true)
            ->with('items', $items->paginate(5 * 8))->with('title', 'সকল বিষয়');
    }

    /**
     * Show Category
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function category(Request $request, $slug)
    {
        // $ids = explode(",", $slug);
        if ($slug == 'cg' && $request->id) {
            $cg = Setting::where('status', 1)->where('id', $request->id)->where('name', 'LIKE', 'book_home_block%')->firstOrFail();
            if (@$cg->value->category) {
                $locale = (App::currentLocale() == 'en') ? '' : '_bn';
                $_title = explode("|", $cg->value->title);
                if ($locale == 'en') {$_title = $_title[0];} else { $_title = (count($_title) > 1) ? $_title[1] : $_title[0];}
                $items = Book::whereIn('status', [1, 3])->whereIn('category_id', $cg->value->category);
            } else {
                return abort(404);
            }
        } else if (preg_match('/^[0-9]+$/', $slug)) {
            $category = Category::where('id', $slug)->FirstorFail();
            return redirect('category/' . $category->slug);
        } else {
            $category = Category::where('slug', $slug)->FirstorFail();
            $items    = Book::whereIn('status', [1, 3])->where('category_id', $category->id);
        }
        if ($request->year) {$items = $items->whereYear('published_at', $request->year);}
        if ($request->month) {$items = $items->whereMonth('published_at', $request->month);}
        if ($request->rating) {$items = $items->whereLike('rating_review', '"rating":' . $request->rating . ",%");}
        if (@$category) {
            return view('books.archive')->with('category', $category)->with('items', $items->paginate(6 * 8))->with('title', @$category->name_bn);
        } else {
            return view('books.archive')->with('other', true)->with('items', $items->paginate(6 * 8))->with('title', $_title);
        }
    }

    /**
     * Show Publisher List
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function publishers(Request $request)
    {
        $items = new Publication();
        if ($request->search) {
            $items = $items->whereLike(['name', 'name_bn'], $request->search);
        }
        return view('books.archives')->with('publishers', true)
            ->with('items', $items->paginate(5 * 8))->with('title', 'সকল প্রকাশনী');
    }

    /**
     * Show Publisher
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function publisher(Request $request, $slug)
    {
        if (preg_match('/^[0-9]+$/', $slug)) {
            $publisher = Publication::where('id', $slug)->FirstorFail();
            return redirect('publisher/' . $publisher->slug);
        } else {
            $publisher = Publication::where('slug', $slug)->FirstorFail();
        }
        $items = Book::whereIn('status', [1, 3])->where('publisher_id', $publisher->id);
        if ($request->year) {$items = $items->whereYear('published_at', $request->year);}
        if ($request->month) {$items = $items->whereMonth('published_at', $request->month);}
        if ($request->rating) {$items = $items->whereLike('rating_review', '"rating":' . $request->rating . ",%");}
        return view('books.archive')->with('publisher', $publisher)->with('items', $items->paginate(6 * 8))->with('title', $publisher->name_bn);
    }

    /**
     * Books Page
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function books(Request $request)
    {
        $items = Book::whereIn('status', [1, 3]);
        $title = $extra = '';
        if ($request->year) {
            $items = $items->whereYear('published_at', $request->year);
            $title = e2b($request->year) . " এর ";
        }
        if ($request->month) {
            $items = $items->whereMonth('published_at', $request->month);
            $title .= ($request->month == 2) ? " বইমেলার " : bnMonth($request->month) . " মাসের ";
        }
        if ($request->rating) {
            $items = $items->whereLike('rating_review', '"rating":' . $request->rating . ",%");
            $extra = ' (' . e2b($request->rating) . " তারকা)";
        }
        if (in_array($request->type, ['hardcover', 'paperback'])) {
            $items = $items->where('type', $request->type);
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'ebook') {
            $items = $items->where('ebook', '!=', '');
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'audio') {
            $items = $items->where('audio', '!=', '');
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'discount') {
            $items = $items->whereRaw('rate > sale')->select(['*', DB::RAW("rate - sale as discount")])->orderBy('discount', 'desc');
            $extra = " (অতিরিক্ত ছাড়ের বই)";
        }
        if ($request->type == 'populer') {
            // $items = $items->whereRaw('rate > sale')->select(['*', DB::RAW("rate - sale as discount")])->orderBy('discount', 'desc');
            $extra = " (আলোচিত বই)";
        }
        if ($request->lang) {
            $items = $items->where('language', $request->lang);
            $extra = " (" . $request->lang . ")";
        }
        if ($request->path() == 'pre-order') {
            $items = $items->where('pre_order', 1);
            $title = "প্রি-অর্ডার এর ";
        }
        if ($request->q) {
            $items = $items->search($request->get('q'));
        }
        $title = $title . "সকল বই";
        return view('books.archive')->with('other', true)->with('items', $items->paginate(5 * 8))->with('title', $title . $extra);
    }

    /**
     * Bestseller
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bestseller(Request $request)
    {
        $items = Book::whereIn('status', [1, 3]);
        $title = $extra = '';
        if ($request->year) {
            $items = $items->whereYear('published_at', $request->year);
            $title = e2b($request->year) . " এর ";
        }
        if ($request->month) {
            $items = $items->whereMonth('published_at', $request->month);
            $title .= ($request->month == 2) ? " বইমেলার " : bnMonth($request->month) . " মাসের ";
        }
        if ($request->rating) {
            $items = $items->whereLike('rating_review', '"rating":' . $request->rating . ",%");
            $extra = ' (' . e2b($request->rating) . " তারকা)";
        }
        if (in_array($request->type, ['hardcover', 'paperback'])) {
            $items = $items->where('type', $request->type);
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'ebook') {
            $items = $items->where('ebook', '!=', '');
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'audio') {
            $items = $items->where('audio', '!=', '');
            $extra = " (" . $request->type . ")";
        }
        if ($request->type == 'discount') {
            $items = $items->whereRaw('rate > sale')->select(['*', DB::RAW("rate - sale as discount")])->orderBy('discount', 'desc');
            $extra = " (অতিরিক্ত ছাড়ের বই)";
        }
        if ($request->type == 'populer') {
            // $items = $items->whereRaw('rate > sale')->select(['*', DB::RAW("rate - sale as discount")])->orderBy('discount', 'desc');
            $extra = " (আলোচিত বই)";
        }
        if ($request->lang) {
            $items = $items->where('language', $request->lang);
            $extra = " (" . $request->lang . ")";
        }
        $title = $title . "বেস্টসেলার বই";
        return view('books.archive')->with('other', true)->with('items', $items->paginate(5 * 8))->with('title', $title . $extra);
    }

    /**
     * Book Fair
     * @param  Request $request [description]
     * @param  [type]  $year    [description]
     * @return [type]           [description]
     */
    public function bookfair(Request $request, $year = false)
    {
        if (!$year) {$year = date('Y');}
        $bf        = 'best_sell_' . $year;
        $latest    = Book::whereIn('status', [1, 3])->whereMonth('published_at', '02')->whereYear('published_at', $year)->orderBy('published_at', 'desc')->take(8)->get();
        $best_sell = Cache::remember($bf, 60 * 5, function () use ($year) {
            $best_sell = DB::table('sales_matrics')
                ->whereMonth('created_at', '02')->whereYear('created_at', $year)
                ->groupBy('book_id')->select(['book_id', DB::RAW('COUNT(book_id) as total')])->orderBy('total', 'desc')->take(8)->get()->pluck('book_id')->toArray();
            return $best_sell;
        });
        $best_sell = Book::whereIn('id', $best_sell)->whereIn('status', [1, 3])->whereMonth('published_at', '02')->whereYear('published_at', $year)->take(8)->get();

        $list[] = ['title' => 'সর্বশেষ প্রকাশিত বইসমূহ', 'link' => url('books?year=' . $year . '&sort=latest'), 'items' => $latest];
        $list[] = ['title' => 'বইমেলার জনপ্রিয় বই', 'link' => url('boimela/' . $year . '?sort=bestseller'), 'items' => $best_sell];
        $list[] = ['title' => 'আত্ম-উন্নয়ন', 'link' => url('boimela/' . $year . '?category=11,229'), 'items' => [], 'cats' => [11, 229]];
        $list[] = ['title' => 'উপন্যাস', 'link' => url('boimela/' . $year . '?category=17,75,79,122'), 'items' => [], 'cats' => [17, 75, 79, 122]];
        $list[] = ['title' => 'ধর্মীয় বই', 'link' => url('boimela/' . $year . '?category=29,4,8,96,93,68,297,194'), 'items' => [], 'cats' => [29, 4, 8, 96, 93, 68, 297, 194]];
        $list[] = ['title' => 'সায়েন্স ফিকশন', 'link' => url('boimela/' . $year . '?category=2,71,524,675'), 'items' => [], 'cats' => [2, 71, 524, 675]];
        $list[] = ['title' => 'গোয়েন্দা ও ভৌতিক', 'link' => url('boimela/' . $year . '?category=512,56,418'), 'items' => [], 'cats' => [512, 56, 418]];
        $list[] = ['title' => 'ইতিহাস ও রাজনীতি', 'link' => url('boimela/' . $year . '?category=433,609,3,245,393,194'), 'items' => [], 'cats' => [433, 609, 3, 245, 393]];
        $list[] = ['title' => 'গণিত, বিজ্ঞান ও প্রযুক্তি', 'link' => url('boimela/' . $year . '?category=23,84,172,282'), 'items' => [], 'cats' => [23, 84, 172, 282]];
        $list[] = ['title' => 'বাংলাদেশ ও মুক্তিযুদ্ধ', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'রম্য ও ব্যঙ্গ রচনা', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'রচনাসমগ্র ও সংকলন', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'কমিক ও গ্রাফিক', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'জীবনী ও স্মৃতিচারণ', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'প্রবন্ধ', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'শিশু ও কিশোর', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'অনুবাদ বই', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'ভ্রমণ ও প্রবাস', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];
        $list[] = ['title' => 'রান্না, স্বাস্থ্য ও পরিবার', 'link' => url('boimela/' . $year . '?category=1'), 'items' => [], 'cats' => []];

        return view('books.bookfair')->with('bookfair', true)
            ->with('list', $list)
            ->with('title', 'বইমেলা - ' . e2b($year))
            ->with('month', '02')
            ->with('year', e2b($year))
            ->with('_year', $year);
    }

    /**
     * Ajax Category
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxCategory(Request $request)
    {
        $query = DB::table('categories');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name_bn', 'name'], $q);
            }
        }
        $items         = $query->select(['id', 'name_bn as text', 'slug'])->take(10)->get()->toArray();
        $re['results'] = $items;
        return $re;
    }

    /**
     * Get Ajax Author
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxAuthor(Request $request)
    {
        $query = DB::table('authors');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9]+)/", $request->q, $m)) {
                $query = $query->where('id', $m[1]);
            } else {
                $query = $query->whereLike(['name_bn', 'name'], $q);
            }
        }
        $items         = $query->select(['id', 'name_bn as text', 'slug'])->take(10)->get()->toArray();
        $re['results'] = $items;
        return $re;
    }

    public function showLegal(Request $request, $slug)
    {
        $item = Page::whereSlug($slug)->where('status', 1)->firstOrFail();
        return view('books.page')->with('item', $item);
    }

    public function postSupport(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'subject' => 'required',
            'details' => 'required',
            'captcha' => 'required|captcha',
        ], ['captcha' => 'Invalid Captcha.']);

        $mail = "From: " . $request->name . "\n";
        $mail .= "Email: " . $request->email . "\n";
        $mail .= "Details: " . clean($request->details);

        Mail::raw($mail, function ($message) use ($request) {
            $message->to('support@boiferry.com')
                ->replyTo($request->email, $request->name)
                ->subject('Support: ' . $request->subject);
        });
        return redirect('support')->with('success', 'Thank you. We will contact you as soon as possible.');
    }

}
