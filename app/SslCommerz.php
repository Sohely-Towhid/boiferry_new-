<?php
namespace App;

use App\Curl;

/**
 * Sslcommerz by Saiful
 */
class SslCommerz
{

    /**
     * sslcommerz
     *
     * @return \Illuminate\Http\Response
     */
    public function sslcommerz(Request $request)
    {
        $input = json_decode($request->cart_json);
        if (!$input) {return abort(404);}

        foreach ($input as $key => $value) {
            $request->merge([$value->name => $value->value]);
        }

        $validator = Validator::make($request->all(), [
            'amount'          => 'required|numeric|min:10|max:10000000',
            'name'            => 'required|max:200',
            'email'           => 'required|email|max:100',
            'country'         => 'required',
            'note'            => 'nullable|max:100',
            'payment_gateway' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => $validator->errors()->first(),
            ]);
        }

        $payment_method = ($request->payment_gateway == 4) ? 'ssl' : 'bank';
        $student        = $campaign        = false;
        $student_id     = 0;
        $user           = User::where('email', $request->email)->orWhere('mobile', $request->mobile)->first();
        if (!$user) {
            $user = User::create(['name' => $request->name, 'mobile' => $request->mobile, 'email' => $request->email, 'status' => 1, 'country' => $request->country]);
        }
        if (Auth::id()) {$user = Auth::user();}

        $details = ['created from ' . request()->ip()];
        if ($request->student_id) {
            $ids     = explode(',', $request->student_id);
            $student = Student::whereIn('id', $ids)->where('status', 1)->get();
            if (count($student) == 0) {return abort(404);}
            $month      = floor($request->get('amount', 1500 * count($student)) / (1500 * count($student)));
            $details[]  = 'SID:' . implode(",", $student->pluck('id')->toArray());
            $amount     = 1500 * $month * count($student);
            $student_id = 1;
        } else {
            $campaign = Campaign::findOrFail($request->campaign_id);
            $amount   = $request->amount;
        }

        $payment              = new Payment();
        $payment->student_id  = $student_id;
        $payment->campaign_id = $request->get('campaign_id', 0);
        $payment->user_id     = $user->id;
        $payment->amount      = $amount;
        $payment->method      = $payment_method;
        $payment->trxid       = '';
        $payment->anonymous   = ($request->anonymous == '1') ? "1" : "0";
        $payment->details     = $details;
        $payment->status      = 0;
        $payment->save();

        $post_data                 = [];
        $post_data['shop_name']    = config('app.ssl.shop_name');
        $post_data['store_id']     = config('app.ssl.store_id');
        $post_data['store_passwd'] = config('app.ssl.store_passwd');
        $post_data['total_amount'] = $payment->amount;
        $post_data['currency']     = "BDT";
        $post_data['tran_id']      = $payment->id . "_" . uniqid();
        $post_data['success_url']  = url('payment/sslcommerz/ipn');
        $post_data['fail_url']     = url('payment/sslcommerz/ipn');
        $post_data['cancel_url']   = url('payment/sslcommerz/ipn');

        // $poc_co = $invoice->postal_code;
        $poc_co = rand(1000, 1205);
        # CUSTOMER INFORMATION
        $post_data['cus_name']     = $user->name;
        $post_data['cus_email']    = $user->email;
        $post_data['cus_add1']     = "Dhaka";
        $post_data['cus_add2']     = '';
        $post_data['cus_city']     = 'Dhaka';
        $post_data['cus_state']    = '';
        $post_data['cus_postcode'] = $poc_co;
        $post_data['cus_country']  = $user->country;
        $post_data['cus_phone']    = $user->mobile;
        $post_data['cus_fax']      = "";

        $post_data['ship_name']     = $user->name;
        $post_data['ship_email']    = $user->email;
        $post_data['ship_add1']     = "Dhaka";
        $post_data['ship_add2']     = '';
        $post_data['ship_city']     = 'Dhaka';
        $post_data['ship_state']    = '';
        $post_data['ship_postcode'] = $poc_co;
        $post_data['ship_country']  = $user->country;
        $post_data['ship_phone']    = $user->mobile;
        $post_data['ship_fax']      = "";

        # Product Information
        if ($campaign) {
            $post_data['product_name'] = $campaign->title . "x 1";
            $post_data['cart']         = [['product' => $campaign->title . "x 1", 'amount' => $payment->amount]];
        }
        if ($student) {
            $post_data['product_name'] = 'Sponsor a Child' . " x" . count($student);
            $post_data['cart']         = [['product' => 'Sponsor a Child #' . implode(",", $student->pluck('id')->toArray()) . "x 1", 'amount' => $payment->amount]];
        }
        $post_data['product_category'] = "Donation";
        $post_data['product_profile']  = "non-physical-goods";
        $post_data['shipping_method']  = "Digital";

        $ch  = new Curl();
        $ssl = $ch->post(config('app.ssl.process'), '', $post_data);
        Cache::add('ssl_' . $payment->id, $ssl, now()->addMinutes(10));
        $ssl = json_decode($ssl, true);
        return response()->json(['status' => 'success', 'data' => $ssl['GatewayPageURL'], 'logo' => $ssl['storeLogo']]);
    }

    /**
     * Sslcommerz IPN
     *
     * @return \Illuminate\Http\Response
     */
    public function sslcommerz_ipn(Request $request)
    {
        if ($request->tran_id && $request->val_id) {
            $trns    = preg_replace('/(_.*)/', '', $request->tran_id);
            $invoice = Payment::find($trns);
            if (!$invoice) {return abort(404);}
            if ($invoice->approved > 1) {return abort(404);}

            $student = $campaign = false;
            if ($invoice->student_id > 0) {
                if (preg_match("/SID:([0-9,]+)/", json_encode($invoice->details), $m)) {
                    $student = Student::whereIn('id', explode(",", $m[1]))->where('status', 1)->get();
                    if (count($student) == 0) {
                        return abort(404);
                    }
                } else {
                    return abort(404);
                }
            } else {
                $campaign = Campaign::findOrFail($invoice->campaign_id);
            }

            $amount               = $invoice->amount;
            $ssl                  = json_decode(cache('ssl_' . $trns));
            $ch                   = new Curl();
            $post['val_id']       = $request->val_id;
            $post['store_id']     = config('app.ssl.store_id');
            $post['store_passwd'] = config('app.ssl.store_passwd');
            $post['format']       = 'json';
            $data                 = json_decode($ch->get(config('app.ssl.check') . '?' . http_build_query($post)));
            if ($data) {
                if (in_array($data->status, ['VALID', 'VALIDATED']) && $data->amount >= $amount) {
                    $invoice->trxid   = 'sslcommerz#' . $request->tran_id;
                    $invoice->status  = "1";
                    $details          = $invoice->details;
                    $details[]        = json_encode($data);
                    $invoice->details = $details;
                    $invoice->save();
                    $this->processPayment($invoice);
                    if ($campaign) {
                        return redirect('campaign/' . $campaign->slug)->with('success', 'Thank you for the donation.');
                    } else {
                        return redirect('sponsor-a-child/' . $invoice->student_id)->with('success', 'Thank you for the donation.');
                    }
                }
            } else {
                if ($invoice->status == 0) {
                    $invoice->delete();
                }
                if ($campaign) {
                    return redirect('campaign/' . $campaign->slug)->with('error', 'Payment Processing Failed, Please Try Again!');
                } else {
                    return redirect('sponsor-a-child/' . $sudent->id)->with('error', 'Payment Processing Failed, Please Try Again!');
                }
            }
        }
        return abort(404);
    }
}
