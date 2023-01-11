<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class FixOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $invoices = Invoice::where('status', 3)->get();
        foreach ($invoices as $key => $invoice) {
            if (!$invoice->shipment_date) {
                $invoice->timestamps    = false;
                $invoice->shipment_date = date('Y-m-d', strtotime($invoice->updated_at . " -3 days"));
                $invoice->save();
            }
        }

        $invoices = Invoice::where('status', 4)->get();
        foreach ($invoices as $key => $invoice) {
            if (!$invoice->shipment_date) {
                $invoice->timestamps    = false;
                $invoice->delivery_date = date('Y-m-d', strtotime($invoice->updated_at));
                $invoice->shipment_date = date('Y-m-d', strtotime($invoice->updated_at . " -3 days"));
                $invoice->save();
            }
        }
        return 0;
    }
}
