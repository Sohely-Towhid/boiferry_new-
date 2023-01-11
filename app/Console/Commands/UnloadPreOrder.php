<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class UnloadPreOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock Pre Order';

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
        // UPDATE `invoices` SET `pre_order` = 1 WHERE  `status` IN (1,2)
        $items = Invoice::with(['metas', 'metas.book'])->where('status', '>', 0)->where('pre_order', 1)->get();
        foreach ($items as $key => $invoice) {
            $pre_order = 0;
            foreach ($invoice->metas as $key1 => $meta) {
                if ($meta->book->pre_order) {
                    $pre_order          = 1;
                    $product            = $meta->product;
                    $product->pre_order = 1;
                    $meta->product      = $product;
                    $meta->save();
                } else {
                    $product            = $meta->product;
                    $product->pre_order = 0;
                    $meta->product      = $product;
                    $meta->save();
                }
            }
            if ($pre_order == 0) {
                $system_note          = $invoice->system_note;
                $system_note[]        = 'Pre Order -> Reguler Order';
                $invoice->pre_order   = 0;
                $invoice->system_note = $system_note;
                $invoice->save();
                echo "Pre Order -> Unlock -> " . $invoice->id . "\n";
            } else {
                $invoice->pre_order = 1;
                $invoice->save();
            }
        }
        return 0;
    }
}
