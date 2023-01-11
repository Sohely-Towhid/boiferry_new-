<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Payout;
use DB;
use Illuminate\Console\Command;

class MakePayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:payout';

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
        $year  = date("Y");
        $month = date("m");
        $items = Payment::where('status', 0)->groupBy('vendor_id')->get();
        foreach ($items as $key => $item) {
            $payout = Payout::where('vendor_id', $item->vendor_id)->where('status', 0)->first();
            if (!$payout) {
                $payout = new Payout();
            }
            Payment::where('vendor_id', $item->vendor_id)->where('status', 0)->update(['status' => 3]);
            $amount            = Payment::where('vendor_id', $item->vendor_id)->where('status', 3)->select(DB::RAW('sum(amount) as amount, SUM(fee) as fee, SUM(pg_fee) as pg_fee'))->first();
            $payout->vendor_id = $item->vendor_id;
            $payout->user_id   = 0;
            $payout->amount += $amount->amount;
            $payout->fee += $amount->fee;
            $payout->pg_fee += $amount->pg_fee;
            $payout->status = 0;
            $payout->save();
            Payment::where('vendor_id', $item->vendor_id)->where('status', 3)->update(['status' => 1]);
        }
        return 0;
    }
}
