<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\InvoiceMeta;
use DB;
use Illuminate\Console\Command;

class BoughtTogether extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bought:together';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bought Together';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $books = DB::table('books')->where('status', 1)->whereNull('fbt')->take(5000)->get();
        foreach ($books as $_book) {
            $book             = Book::where('id', $_book->id)->first();
            $data             = InvoiceMeta::where('book_id', $book->id)->take(30)->get()->pluck('invoice_id')->toArray();
            $data             = InvoiceMeta::whereIn('invoice_id', $data)->where('book_id', '!=', $book->id)->groupBy('book_id')->get()->pluck('book_id');
            $bt               = Book::whereIn('id', $data)->inRandomOrder()->take(8)->get()->pluck('id')->toArray();
            $book->timestamps = false;
            $book->fbt        = $bt;
            $book->save();
            echo $book->id . '.';
        }
        return 0;
    }
}
