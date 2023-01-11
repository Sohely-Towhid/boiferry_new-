<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Invoice;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    /**
     * Report Handler
     * @param  Request $request [description]
     * @param  [type]  $name    [description]
     * @return [type]           [description]
     */
    public function reportProcess(Request $request, $name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}($request);
        }
        return abort(404);
    }

    /**
     * Sales Report
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sales(Request $request)
    {
        $date        = explode(" - ", $request->get('date', date('m/d/Y - m/d/Y')));
        $date[0]     = date('Y-m-d', strtotime($date[0]));
        $date[1]     = date('Y-m-d', strtotime($date[1]));
        $status      = $request->get('status', 4);
        $date_filter = ($status == 4) ? 'delivery_date' : 'created_at';
        $items       = Invoice::where('status', $status)->whereBetween(DB::RAW('DATE(' . $date_filter . ')'), $date);
        $title       = 'Sales report of ' . implode(" to ", $date);
        if ($request->payment && $request->payment != 'All') {
            $items = $items->where('payment', $request->payment);
            $title = 'Sales report of ' . implode(" to ", $date) . ' of PG: ' . strtoupper($request->payment);
        }
        return view('report.sales')->with('items', $items->get())->with('title', $title);
    }

    /**
     * Book wise report
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function book(Request $request)
    {
        $date    = explode(" - ", $request->get('date', date('m/d/Y - m/d/Y')));
        $date[0] = date('Y-m-d 00:00:00', strtotime($date[0]));
        $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
        $type    = $request->get('type', 'book');
        $select  = [];
        if ($type == 'book') {
            $groupBy = 'books.id';
            $select  = ['books.id', 'books.title_bn as book_name', 'books.author_bn as author_name', 'publications.name_bn as publisher_name', DB::RAW('sum(quantity) as total'), 'books.buy', 'books.rate', 'books.sale'];
        }
        if ($type == 'author') {
            $groupBy = 'books.author_id';
            $select  = ['books.id', 'books.author_bn as author_name', DB::RAW('sum(quantity) as total')];
        }
        if ($type == 'publisher') {
            $groupBy = 'books.publisher_id';
            $select  = ['books.id', 'publications.name_bn as publisher_name', DB::RAW('sum(quantity) as total')];
        }
        $title = 'Book wise sales report of ' . implode(" to ", $date);
        $items = DB::table('invoices')->whereIn('invoices.status', [2, 3, 4, 6, 7])
            ->whereBetween('invoices.created_at', $date)
        // ->where('invoice_metas.book_id', 285)
            ->join('invoice_metas', 'invoices.id', 'invoice_metas.invoice_id')
            ->leftjoin('books', 'invoice_metas.book_id', 'books.id')
            ->leftjoin('publications', 'books.publisher_id', 'publications.id')
            ->select($select)
            ->groupBy($groupBy)
            ->get();
        // dd($items);
        // $items = Invoice::whereIn('status', [2, 3, 4, 6, 7])->whereBetween('created_at', $date)->get()->pluck('id')->toArray();
        // $items = InvoiceMeta::with('book')->whereIn('invoice_id', $items)->groupBy('book_id')->select(['book_id', DB::RAW('sum(quantity) as total')])->get();
        return view('report.book')->with('items', $items)->with('title', $title)->with('type', $type);
    }

    /**
     * Stock Report
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function stock(Request $request)
    {
        $title                 = 'Stock Report of ' . date('Y-m-d');
        $items['stock']        = Book::where('status', 1)->where('stock', '<=', 5)->get();
        $items['not_in_stock'] = Book::where('status', 1)->where('stock', 0)->get();
        return view('report.stock')->with('items', $items)->with('title', $title);
    }

}
