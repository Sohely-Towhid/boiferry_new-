<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class FbFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fb:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make FB Feed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        @ini_set('memory_limit', -1);
        $list[] = ['id', 'title', 'description', 'link', 'availability', 'condition', 'price', 'sale', 'sale_price_effective_date', 'image_link', 'brand', 'google_product_category'];
        $books  = Book::where('status', 1)->get();
        foreach ($books as $key => $book) {
            $title       = mb_strimwidth($book->title_bn . " (" . $book->title . ")", 0, 147, '...', 'utf-8');
            $description = @$book->seo->description;
            if (empty($description)) {
                $description = $book->title . ' by ' . $book->author;
            }
            $description = mb_strimwidth($description, 0, 297, '...', 'utf-8');
            $list[]      = ['bf-' . $book->id, $title, $description, url('book/' . $book->slug), ($book->stock > 0) ? 'in stock' : 'out of stock', 'new', $book->sale . " BDT", $book->sale . " BDT", date('c') . '/' . date('c', strtotime('+1 month')), url('fb-feed?img=' . $book->images[0]), $book->publication->name_bn, 543543];
        }
        $this->array2csv($list);
        return 0;
    }
}
