<?php

namespace App\Http\Controllers;

use App\BkashTokenizedCheckout;
use App\Curl;
use App\Models\Invoice;
use App\Models\Setting;
use Auth;
use Cache;
use DGvai\Nagad\Facades\Nagad;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show User Dahboard
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function dashboard(Request $request)
    {
        return view('account.dashboard');
    }

    /**
     * All Orders
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function order(Request $request)
    {
        return view('account.order');
    }

    /**
     * Single Order
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function orderShow(Request $request, $id)
    {
        $user    = Auth::user();
        $invoice = Invoice::with('metas')->where('id', $id)->where('user_id', $user->id)->firstOrFail();
        return view('account.order-single')->with('item', $invoice);
    }

    /**
     * Order SSL
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function orderPost(Request $request, $id)
    {
        $user    = Auth::user();
        $invoice = Invoice::with('metas')->where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $total   = $invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount;
        // dd($request->nagad);
        /**
         * Bkash Payment
         */
        if ($request->bkash == 'true' && $invoice->status == 1) {
            if ($invoice->payment == 'cod') {
                $total = 100; // 100 tk advance
            }
            $bkash = new BkashTokenizedCheckout();
            $url   = $bkash->createPayment($total, $invoice->id, 'bcu_' . $invoice->id);
            if ($url) {
                return $this->success(['url' => $url]);
            } else {
                return $this->error(['error' => 'Something went wrong!']);
            }
        }
        /**
         * Nagad Payment
         */
        if ($request->nagad == 'true' && $invoice->status == 1) {
            if ($invoice->payment == 'cod') {
                $total = 100; // 100 tk advance
            }
            $url = Nagad::setOrderID("BFX" . $invoice->id . "X" . rand(1, 999))
                ->setAmount($total)
                ->checkout()
                ->getRedirectUrl();
            if ($url) {
                return $this->success(['url' => $url]);
            } else {
                return $this->error(['error' => 'Something went wrong!']);
            }
        }
        /**
         * SSL Payment
         * @var [type]
         */
        if (in_array($invoice->payment, ['sslcommerz', 'cod']) && $invoice->status == 1) {
            if ($invoice->payment == 'cod') {
                $total = 100; // 100 tk advance
            }
            $post_data                 = [];
            $post_data['shop_name']    = config('services.ssl.shop_name');
            $post_data['store_id']     = config('services.ssl.store_id');
            $post_data['store_passwd'] = config('services.ssl.store_passwd');
            $post_data['total_amount'] = $total;
            $post_data['currency']     = "BDT";
            $post_data['tran_id']      = $invoice->id . "_" . uniqid();
            $post_data['success_url']  = url('api/payment/sslcommerz/ipn');
            $post_data['fail_url']     = url('api/payment/sslcommerz/ipn');
            $post_data['cancel_url']   = url('api/payment/sslcommerz/ipn');

            # CUSTOMER INFORMATION
            $post_data['cus_name']     = $invoice->billing_address->name;
            $post_data['cus_email']    = $invoice->billing_address->email;
            $post_data['cus_add1']     = $invoice->billing_address->street;
            $post_data['cus_add2']     = '';
            $post_data['cus_city']     = $invoice->billing_address->city;
            $post_data['cus_state']    = $invoice->billing_address->district;
            $post_data['cus_postcode'] = ($invoice->billing_address->postcode) ? $invoice->billing_address->postcode : rand(1000, 1205);
            $post_data['cus_country']  = $invoice->billing_address->country;
            $post_data['cus_phone']    = $invoice->billing_address->mobile;
            $post_data['cus_fax']      = "";

            $post_data['ship_name']     = $invoice->shipping_address->name;
            $post_data['ship_email']    = $invoice->shipping_address->email;
            $post_data['ship_add1']     = $invoice->shipping_address->street;
            $post_data['ship_add2']     = '';
            $post_data['ship_city']     = $invoice->shipping_address->city;
            $post_data['ship_state']    = $invoice->shipping_address->district;
            $post_data['ship_postcode'] = ($invoice->shipping_address->postcode) ? $invoice->shipping_address->postcode : rand(1000, 1205);
            $post_data['ship_country']  = $invoice->shipping_address->country;
            $post_data['ship_phone']    = $invoice->shipping_address->mobile;
            $post_data['ship_fax']      = "";

            # Product Details
            $post_data['product_name']     = "Invoice {$invoice->id} x 1";
            $post_data['cart']             = [['product' => $post_data['product_name'], 'amount' => $total]];
            $post_data['product_category'] = "Physical Goods";
            $post_data['product_profile']  = "physical-goods";
            $post_data['shipping_method']  = "Courier";

            $ch  = new Curl();
            $ssl = $ch->post(config('services.ssl.process'), '', $post_data);
            Cache::add('ssl_' . $invoice->id, $ssl, now()->addMinutes(10));
            $ssl = json_decode($ssl, true);
            return response()->json(['status' => 'success', 'data' => $ssl['GatewayPageURL'], 'logo' => $ssl['storeLogo']]);
        }

    }

    /**
     * SSL Subscription Post
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subscriptionPost(Request $request)
    {
        $user         = Auth::user();
        $subscription = Setting::getValue('subscription_' . $request->get('order', 1));
        if (!$subscription) {return abort(404);}
        $total = $subscription;

        $post_data                 = [];
        $post_data['shop_name']    = config('services.ssl.shop_name');
        $post_data['store_id']     = config('services.ssl.store_id');
        $post_data['store_passwd'] = config('services.ssl.store_passwd');
        $post_data['total_amount'] = $total;
        $post_data['currency']     = "BDT";
        $post_data['tran_id']      = $user->id . "_" . $request->get('order', 1) . '_' . uniqid();
        $post_data['success_url']  = url('api/subscription/sslcommerz/ipn');
        $post_data['fail_url']     = url('api/subscription/sslcommerz/ipn');
        $post_data['cancel_url']   = url('api/subscription/sslcommerz/ipn');

        # CUSTOMER INFORMATION
        $post_data['cus_name']     = $user->name;
        $post_data['cus_email']    = $user->email;
        $post_data['cus_add1']     = 'Dhaka';
        $post_data['cus_add2']     = '';
        $post_data['cus_city']     = 'Dhaka';
        $post_data['cus_state']    = 'Dhaka';
        $post_data['cus_postcode'] = rand(1000, 1205);
        $post_data['cus_country']  = 'Bangladesh';
        $post_data['cus_phone']    = $user->mobile;
        $post_data['cus_fax']      = "";

        $post_data['ship_name']     = $user->name;
        $post_data['ship_email']    = $user->email;
        $post_data['ship_add1']     = 'Dhaka';
        $post_data['ship_add2']     = '';
        $post_data['ship_city']     = 'Dhaka';
        $post_data['ship_state']    = 'Dhaka';
        $post_data['ship_postcode'] = rand(1000, 1205);
        $post_data['ship_country']  = 'Bangladesh';
        $post_data['ship_phone']    = $user->mobile;
        $post_data['ship_fax']      = "";

        # Product Details
        $post_data['product_name']     = "Boiferry Subscription x " . $request->get('order', 1) . " Month";
        $post_data['cart']             = [['product' => $post_data['product_name'], 'amount' => $total]];
        $post_data['product_category'] = "Subscription";
        $post_data['product_profile']  = "non-physical-goods";
        $post_data['shipping_method']  = "NO";

        $ch  = new Curl();
        $ssl = $ch->post(config('services.ssl.process'), '', $post_data);
        $ssl = json_decode($ssl, true);
        return response()->json(['status' => 'success', 'data' => $ssl['GatewayPageURL'], 'logo' => $ssl['storeLogo']]);
    }

    /**
     * Show Profile
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        if ($request->dob) {
            $user->dob = $request->dob;
            $user->save();
            return $this->success('**ok**');
        }
        return view('account.profile');
    }

    /**
     * Update Profile
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postProfile(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'old_password'          => 'nullable|required_with:password_confirmation|required_with:password|current_password:web',
            'password'              => 'nullable:old_password,password_confirmation|confirmed',
            'password_confirmation' => 'nullable:password,old_password',
        ]);

        $user       = Auth::user();
        $user->name = $request->name;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect('my-account/profile')->with('success', 'Profile Updated Successfully!');
    }

    /**
     * Show Wishlist
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function wishlist(Request $request)
    {
        return view('account.wishlist');
    }

    /**
     * Support Page
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function support(Request $request)
    {
        return view('account.support');
    }

    /**
     * Subscription Page
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subscription(Request $request)
    {
        return view('account.subscription');
    }
}
