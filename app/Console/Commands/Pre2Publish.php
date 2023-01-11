<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class Pre2Publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre:pub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre Order to Publish';

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
        $books = Book::where('pre_order', 1)->get();
        foreach ($books as $key => $book) {
            if (strtotime($book->published_at) <= strtotime(date('Y-m-d'))) {
                $book->pre_order = 0;
                $book->save();
                echo "Pub " . $book->id . "\n";
            }
        }
        return 0;
    }
}
