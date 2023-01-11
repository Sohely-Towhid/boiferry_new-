<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
use App\Winx;
use Illuminate\Console\Command;

class WinxSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winx:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Winx Sync';

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
        $winx = new Winx();
        // $parcel = $winx->getParcel();
        // 3 Shipped
        // 4 Completed
        // 5 Cancelled
        $invoices = Invoice::where('status', 3)->get();
        foreach ($invoices as $invoice) {
            if (preg_match("/WINX:([a-z0-9]{13})/i", $invoice->tracking, $m)) {
                $tracking = $m[1];
                $parcel   = $winx->getParcel($tracking);
                var_dump(@$invoice->id);
                var_dump(@$parcel->status_text);
                if (in_array(@$parcel->status_text, ['Delivered', 'Partial Delivery'])) {
                    $invoice->status        = 4;
                    $invoice->delivery_date = date('Y-m-d');
                    $invoice->save();
                    $c_n  = "App\Notifications\InvoiceCompleted";
                    $user = User::where('id', $invoice->user_id)->first();
                    $user->notify(new $c_n($user, $invoice));
                }
                if (in_array(@$parcel->status_text, ['Awaiting Return', 'Returned', 'Rejected', 'Return Assigned'])) {
                    $invoice->status = 5;
                    $invoice->save();
                    $c_n  = "App\Notifications\InvoiceCanceled";
                    $user = User::where('id', $invoice->user_id)->first();
                    $user->notify(new $c_n($user, $invoice));
                }
                sleep(2);
            }
        }
        return 0;
    }
}
