<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoiceMeta;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInvoice;
use App\Models\Wishlist;
use App\Notifications\AccountCreated;
use App\Notifications\InvoiceCreated;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
use Str;

class WebController extends Controller
{
    public function index(Request $request)
    {
        return view('layouts.web');
    }

    /**
     * Set App Language
     * @param  Request $request [description]
     * @param  [type]  $locale  [description]
     * @return [type]           [description]
     */
    public function locale(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'bn'])) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    }

    /**
     * Ajax Mili Searchar
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxSearch(Request $request)
    {
        $items = Book::search($request->q)->take(20)->get();
        foreach ($items as $key => $item) {
            $items[$key] = ['id' => $item->id, 'title' => $item->title_bn, 'author' => $item->author_bn, 'stock_color' => ($item->stock) ? 'success' : 'danger', 'stock' => ($item->stock) ? 'স্টকে আছে' : 'স্টকে নেই', 'rate' => $item->rate, 'sale' => $item->sale];
        }
        return response()->json($items, JSON_UNESCAPED_UNICODE);
        return json_encode($items, JSON_UNESCAPED_UNICODE);
    }

    public function search(Request $request)
    {
        $xml  = simplexml_load_string(file_get_contents(storage_path('data/author_urls_1.xml')));
        $json = json_decode(json_encode($xml));
        foreach ($json->url as $key => $value) {
            $name         = preg_replace('/.*author\/[0-9]+\/([a-z0-9\-]+)/', '$1', $value->loc);
            $slug         = str_replace(["--", ',', '+'], ['-', '', '-'], $name);
            $name         = ucwords(str_replace(["-"], [' '], $slug));
            $items[$slug] = ['slug' => $slug, 'name' => $name, 'name_bn' => $name, 'created_at' => date('Y-m-d H:i:s')];
        }

        $xml  = simplexml_load_string(file_get_contents(storage_path('data/author_urls_2.xml')));
        $json = json_decode(json_encode($xml));
        foreach ($json->url as $key => $value) {
            $name         = preg_replace('/.*author\/[0-9]+\/([a-z0-9\-]+)/', '$1', $value->loc);
            $slug         = str_replace(["--", ',', '+'], ['-', '', '-'], $name);
            $name         = ucwords(str_replace(["-"], [' '], $slug));
            $items[$slug] = ['slug' => $slug, 'name' => $name, 'name_bn' => $name, 'created_at' => date('Y-m-d H:i:s')];
        }

        // $items = array_chunk($items, 950);
        // onila-salins
        // onila-salins
        foreach ($items as $key => $value) {
            var_dump($key);
        }
    }

    /**
     * Ajax Cart (All)
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxCart(Request $request)
    {
        $user       = Auth::user();
        $user_id    = ($user) ? $user->id : 0;
        $session_id = session()->getId();
        // $type       = (preg_match("/^book|boi/", request()->getHost())) ? 'book' : 'product';
        $type      = 'book';
        $product   = false;
        $this->err = false;
        $ck_inv    = (int) $request->inv;

        if ($request->type != 'applyCoupon' && $request->type != 'giftWrap' && $request->type != 'updateShipping') {
            if ($type == 'book') {
                $product = Book::where('id', $request->product_id)->where('status', 1)->firstOrFail();
                $only    = ['title', 'title_bn', 'rate', 'sale', 'isbn', 'slug', 'images', 'author_bn', 'author', 'type', 'language', 'shelf', 'pre_order'];
            } else {
                $product = Product::where('id', $request->product_id)->where('status', 1)->firstOrFail();
                $only    = ['name', 'rate', 'sale', 'color', 'size', 'sku', 'slug', 'images', 'shelf'];
            }
        }

        $column = ($type == 'book') ? 'book_id' : 'product_id';

        if ($product && $product->stock == 0 && $request->type != 'add2wishlist') {
            return $this->error('Product is not in stock!', 406);
        }

        if ($user) {
            Wishlist::where('user_id', $user->id)->where($column, $request->product_id)->delete();
        }

        $invoice = Invoice::where('session', $session_id);
        if ($ck_inv) {
            $invoice = $invoice->where('id', $ck_inv)->where('status', 0);
        }
        $invoice = $invoice->first();
        if (!$invoice) {
            $invoice = Invoice::create(['user_id' => $user_id, 'processed_by' => 0, 'address_id' => 0, 'session' => $session_id, 'status' => 0]);
        }

        if ($request->type == 'giftWrap') {
            $invoice->gift_wrap = ($request->value == 'true') ? Setting::getValue('book_home_gift_wrap') : 0;
            $invoice->save();
        }

        if ($request->type == 'updateShipping') {
            $invoice->payment = $request->pg;
            $invoice->save();
        }

        if ($request->type == 'applyCoupon') {
            $this->applyCoupon($request, $invoice);
        }

        if ($request->type == 'add2cart') {
            $meta = InvoiceMeta::where('invoice_id', $invoice->id)->where($column, $product->id)->first();
            if ($meta) {
                $meta->quantity += $request->quantity;
                $meta->rate     = $product->sale;
                $meta->discount = $product->rate - $product->sale;
                $meta->product  = $product->only($only);
                $meta->save();
            } else {
                $meta = InvoiceMeta::create(['invoice_id' => $invoice->id, 'vendor_id' => $product->vendor_id, $column => $product->id, 'quantity' => $request->quantity, 'rate' => $product->sale, 'discount' => $product->rate - $product->sale, 'other_data', 'product' => $product->only($only)]);
            }
        }

        if ($request->type == 'rem4mcart') {
            InvoiceMeta::where('invoice_id', $invoice->id)->where($column, $product->id)->delete();
        }

        if ($request->type == 'updateCart' && $request->quantity != 0) {
            InvoiceMeta::where('invoice_id', $invoice->id)->where($column, $product->id)->update(['quantity' => $request->quantity]);
        }

        if ($request->type == 'add2wishlist') {
            if (!$user) {
                return $this->success('login first', 406);
            }
            $wl = Wishlist::where('user_id', $user->id)->where($column, $product->id)->first();
            if (!$wl) {
                Wishlist::create(['user_id' => $user->id, $column => $product->id]);
            }
        }
        $this->calculateInvoice($invoice);
        $this->applyCoupon($request, $invoice);
        if ($this->err) {
            return $this->success($this->err, 406);
        }
        return $this->success('cart updated', 201);
    }

    /**
     * Update Shipping Charge
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function updateShipping($district = '', $pg = '')
    {
        if (empty($district)) {
            $district = request()->district;
        }
        if ($district == 'Dhaka') {
            return Setting::getValue((preg_match("/cod/", $pg)) ? 'book_home_shipping_cod' : 'book_home_shipping');
        } else {
            return Setting::getValue((preg_match("/cod/", $pg)) ? 'book_home_shipping_out_cod' : 'book_home_shipping_out');
        }
    }

    public function emptyCoupon($cart)
    {
        $cart->coupon_discount = 0;
        $cart->coupon          = null;
        $cart->save();
        return $cart;
    }

    /**
     * Apply Coupon Code
     * @param  [type] $request [description]
     * @param  [type] $cart    [description]
     * @return [type]          [description]
     */
    public function applyCoupon($request, $cart)
    {
        $coupon          = null;
        $coupon_discount = 0;
        $this->err       = '';
        $coupon_code     = (@$request->code) ? $request->code : $cart->coupon;
        $cart_total      = $cart->total + $cart->shipping + $cart->gift_wrap;
        if ($coupon_code) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('status', 1)
                ->whereDate('start', '<=', date('Y-m-d'))
                ->whereDate('expire', '>=', date('Y-m-d'))
                ->firstOrFail();
            $metas = InvoiceMeta::with('book')->where('invoice_id', $cart->id)->get();

            // Book ID Only (No Min Max)
            if ($coupon->book_id) {
                $coupon_discount = 0;
                foreach ($metas as $key => $meta) {
                    if (in_array($meta->book_id, $coupon->book_id)) {
                        if ($coupon->type == 1) {
                            $coupon_discount += $meta->quantity * $coupon->amount;
                        } else {
                            $coupon_discount += ($meta->rate * $meta->quantity * $coupon->amount) / 100;
                        }
                    }
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                    return true;
                } else {
                    $cart = $this->emptyCoupon($cart);
                    return true;
                }
                return true;
            }

            // User ID Only (No Min Max)
            if ($coupon->user_id) {
                $coupon_discount = 0;
                if ($cart->user_id != $coupon->user_id) {
                    $this->err = 'This coupon is not valid for you.';
                    $cart      = $this->emptyCoupon($cart);
                    return true;
                }
                $amount = $quantity = 0;
                foreach ($metas as $meta) {
                    $amount += ($meta->quantity * $meta->rate);
                    $quantity += $meta->quantity;
                }
                if ($coupon->type == 1) {
                    $coupon_discount = $quantity * $coupon->amount;
                } else {
                    $coupon_discount = ($amount * $coupon->amount) / 100;
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                }
                return true;
            }

            // Author Only (No Min Max)
            if ($coupon->author_id) {
                $coupon_discount = 0;
                foreach ($metas as $key => $meta) {
                    if (in_array($meta->book->author_id, $coupon->author_id)) {
                        if ($coupon->type == 1) {
                            $coupon_discount += $meta->quantity * $coupon->amount;
                        } else {
                            $coupon_discount += ($meta->rate * $meta->quantity * $coupon->amount) / 100;
                        }
                    }
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                    return true;
                } else {
                    $cart = $this->emptyCoupon($cart);
                    return true;
                }
                return true;
            }

            // Publisher Only
            if ($coupon->publisher_id) {
                $coupon_discount = 0;
                foreach ($metas as $key => $meta) {
                    if (in_array($meta->book->publisher_id, $coupon->publisher_id)) {
                        if ($coupon->type == 1) {
                            $coupon_discount += $meta->quantity * $coupon->amount;
                        } else {
                            $coupon_discount += ($meta->rate * $meta->quantity * $coupon->amount) / 100;
                        }
                    }
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                    return true;
                } else {
                    $cart = $this->emptyCoupon($cart);
                    return true;
                }
                return true;
            }

            // Vendor Only
            if ($coupon->vendor_id) {
                $coupon_discount = 0;
                foreach ($metas as $key => $meta) {
                    if (in_array($meta->vendor_id, $coupon->vendor_id)) {
                        if ($coupon->type == 1) {
                            $coupon_discount += $meta->quantity * $coupon->amount;
                        } else {
                            $coupon_discount += ($meta->rate * $meta->quantity * $coupon->amount) / 100;
                        }
                    }
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                    return true;
                } else {
                    $cart = $this->emptyCoupon($cart);
                    return true;
                }
                return true;
            }

            // Plain Coupon + Vendor
            if ($coupon->min_shopping > 0 && $cart_total < $coupon->min_shopping) {
                if ($coupon->vendor_id != $value->vendor_id) {
                    $cart->coupon_discount = 0;
                    $cart->coupon          = null;
                    $cart->save();
                    $this->err = "coupon is not valid for this shop.";
                    return;
                }
            } else {
                if ($coupon->type == 1) {
                    $coupon_discount = $coupon->amount;
                } else {
                    $coupon_discount = ($cart_total * $coupon->amount) / 100;
                }
                if ($coupon_discount > 0) {
                    $cart->coupon_discount = $coupon_discount;
                    $cart->coupon          = $coupon_code;
                    $cart->save();
                    return true;
                } else {
                    $cart = $this->emptyCoupon($cart);
                    return true;
                }
                return true;
            }

            // End of Coupon
        }
    }

    /**
     * Calculate Invoice Details
     * @param  Invoice $invoice [description]
     * @return [type]           [description]
     */
    public function calculateInvoice(Invoice $invoice)
    {
        $free      = Setting::getValue('book_home_free_shipping');
        $total     = $coupon_discount     = $discount     = $pre_order     = 0;
        $magix_box = false;
        foreach ($invoice->metas as $key => $meta) {
            if ($meta->book->stock) {
                $total += ($meta->rate * $meta->quantity);
                $discount += ($meta->discount * $meta->quantity);
                if ($meta->product->pre_order) {$pre_order = 1;}
                if ($meta->book_id == 23848) {
                    $magix_box = true;
                }
            } else {
                $meta->delete();
            }
        }
        $invoice->pre_order = $pre_order;
        $invoice->discount  = $discount;
        $invoice->total     = ceil($total);
        $invoice->shipping  = ($total >= $free) ? 0 : $this->updateShipping(@$invoice->shipping_address->district, $invoice->payment);
        if ($invoice->shipping > 0 && $total >= 299 && date('Y-m-d') == '2022-12-31') {
            $invoice->shipping = 22;
        }
        if ($invoice->shipping > 0 && $total >= 299 && date('Y-m-d') == '2023-01-01') {
            $invoice->shipping = 23;
        }
        // $time = date('H');
        // if ($magix_box == true && in_array($time, [15, 16])) {
        //     $invoice->shipping = 0;
        // }
        $invoice->save();
        return $invoice;
    }

    /**
     * Get Cart List JSON
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxCartList(Request $request)
    {
        $session_id = session()->getId();
        $ck_inv     = (int) $request->inv;
        $invoice    = Invoice::with('metas')->where('session', $session_id);
        if ($ck_inv) {
            $invoice = $invoice->where('id', $ck_inv)->where('status', 0);
        }
        $invoice = $invoice->first();
        if ($invoice) {
            $invoice = $invoice->only(['metas', 'total', 'discount', 'coupon_discount', 'shipping', 'gift_wrap']);
        }
        return $this->success($invoice);
    }

    /**
     * Show Cart
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function cart(Request $request)
    {
        $session_id = session()->getId();
        $invoice    = Invoice::with('metas')->where('session', $session_id)->first();
        return view('books.cart')->with('item', $invoice);
    }

    /**
     * Checkout
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkout(Request $request)
    {
        $session_id = session()->getId();
        $sc         = array_filter(explode(",", request()->ids));
        $invoice    = Invoice::with('metas')->where('session', $session_id)->first();
        if ($invoice && $sc) {
            $metas   = InvoiceMeta::where('invoice_id', $invoice->id)->whereIn('id', $sc)->get();
            $invoice = $invoice->replicate();
            $invoice->push();
            foreach ($metas as $meta) {
                $meta               = $meta->toArray();
                $meta['invoice_id'] = $invoice->id;
                InvoiceMeta::create($meta);
            }
            $this->applyCoupon($request, $invoice);
            $invoice = $this->calculateInvoice(Invoice::with('metas')->find($invoice->id));
        }
        return view('books.checkout')->with('item', $invoice);
    }

    /**
     * Post Checkout
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function PostCheckout(Request $request)
    {
        $user       = Auth::user();
        $session_id = session()->getId();
        $ck_inv     = (int) $request->inv;
        $invoice    = Invoice::with('metas')->where('session', $session_id);
        if ($ck_inv) {
            $invoice = $invoice->where('id', $ck_inv)->where('status', 0);
        }
        $invoice = $invoice->firstOrFail();

        $request->validate([
            'tos'     => 'required',
            'payment' => 'required',
            'email'   => 'required|email',
            'name'    => 'required',
            'mobile'  => 'required|mobile',
            'bill'    => 'required',
        ]);

        if (!$user) {
            if (User::where('email', $request->email)->first()) {
                return redirect('checkout')->withInput()->with('error', 'You have an account with us please login.');
            }
            $password = Str::random(8);
            $user     = User::create(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'password' => bcrypt($password)]);
            // Send Email
            $user->notify(new AccountCreated($user, $password));
        }

        $billing  = Address::create(['user_id' => $user->id, 'name' => $request->name, 'country' => @$request->bill['country'], 'street' => @$request->bill['street'], 'city' => @$request->bill['city'], 'district' => @$request->bill['district'], 'postcode' => @$request->bill['postcode'], 'mobile' => $request->mobile, 'email' => $request->email]);
        $shipping = Address::create(['user_id' => $user->id, 'name' => $request->name, 'country' => @$request->bill['country'], 'street' => @$request->bill['street'], 'city' => @$request->bill['city'], 'district' => @$request->bill['district'], 'postcode' => @$request->bill['postcode'], 'mobile' => $request->mobile, 'email' => $request->email]);

        $invoice->user_id     = $user->id;
        $invoice->billing_id  = $billing->id;
        $invoice->shipping_id = $shipping->id;
        $invoice->save();
        $this->calculateInvoice($invoice);
        $invoice->status  = 1;
        $invoice->note    = $request->note;
        $invoice->note    = $request->note;
        $invoice->payment = $request->payment;
        if (@$request->bill['district'] == 'Dhaka' && $request->payment == 'cod') {
            $invoice->payment = 'cod-full';
        }
        $invoice->session     = null;
        $invoice->created_at  = date('Y-m-d H:i:s');
        $invoice->system_note = ['created from ' . request()->ip(), 'UA:' . @$_SERVER['HTTP_USER_AGENT']];
        $invoice->save();

        // delete cart item for selective
        $meta_ids = array_filter(explode(",", request()->ids));
        if ($meta_ids) {
            $invs = Invoice::where('session', $session_id)->get()->pluck('id')->toArray();
            if ($invs) {
                InvoiceMeta::whereIn('invoice_id', $invs)->whereIn('id', $meta_ids)->delete();
            }
        }

        $vi = [];
        foreach ($invoice->metas as $meta) {
            if (!in_array($meta->vendor_id, $vi)) {
                $vi[] = $meta->vendor_id;
                VendorInvoice::create(['invoice_id' => $invoice->id, 'vendor_id' => $meta->vendor_id, 'status' => 1, 'cod' => ($invoice->payment == 'cod') ? 1 : 0]);
            }
        }

        if (!Auth::check()) {
            Auth::login($user);
        }

        // Send Email
        $user->notify(new InvoiceCreated($user, $invoice));
        // Redirect To Order Page
        return redirect('my-account/order/' . $invoice->id . "?payment=yes")->with('fb', 'yes');
    }

    public function seller(Request $request)
    {
        return view('web.seller');
    }

    public function sellerPost(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email|unique:vendors',
            'mobile'        => 'required|mobile',
            'address'       => 'required',
            'trade_licence' => 'required|mimes:jpg,png,pdf',
            'nid'           => 'required|mimes:jpg,png,pdf',
            'bank'          => 'nullable|mimes:jpg,png,pdf',
            'tin'           => 'nullable|mimes:jpg,png,pdf',
            'bin'           => 'nullable|mimes:jpg,png,pdf',
        ]);

        // $input            = $request->except(['status']);
        $input['name']    = $request->name;
        $input['email']   = $request->email;
        $input['mobile']  = $request->mobile;
        $input['address'] = $request->address;
        $input['book']    = ($request->book) ? 1 : 0;
        $input['user_id'] = Auth::id();

        $input['files']['trade_licence'] = $request->trade_licence->store('internal');
        $input['files']['nid']           = $request->nid->store('internal');
        if ($request->bank) {
            $input['files']['bank'] = $request->bank->store('internal');
        }
        if ($request->tin) {
            $input['files']['tin'] = $request->tin->store('internal');
        }
        if ($request->bin) {
            $input['files']['bin'] = $request->bin->store('internal');
        }

        $item = Vendor::create($input);

        return redirect('become-a-seller')->with('success', 'We received your request. Our seller support team will contact you shortly, this may take few business days.');
    }

    /**
     * FB Feed Image
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function fbFeed(Request $request)
    {
        if ($request->author) {
            return $this->authorOG($request->author);
        }
        if ($request->publisher) {
            return $this->publisherOG($request->publisher);
        }

        if ($request->category) {
            return $this->categoryOG($request->category);
        }

        if (preg_match("/^redactor/", $request->img)) {
            $path = "assets/images/" . $request->img;
            $path = str_replace("redactor/", "redactor/md_", $path);
            if (file_exists(public_path($path))) {
                $image = Image::make(public_path('assets/images/fb-feed.jpeg'));
                $image->insert(public_path($path), 'center');
                return $image->response('png');
            }
        }

        $image = Image::make(public_path('assets/images/fb-feed.jpeg'));
        $image->insert(public_path('assets/images/default-book-md.webp'), 'center');
        return $image->response('png');
    }

    public function split_text($text, $limit = 100, $end = '')
    {
        $text  = str_replace(["\n", "\r"], ' ', $text);
        $text  = str_replace(['  '], ' ', $text);
        $words = explode(" ", $text);
        $cwl   = 0;
        $lines = [];
        $lc    = 0;
        foreach ($words as $wk => $word) {
            $wl = mb_strwidth($word, 'UTF-8');
            if ($cwl + $wl < $limit) {
                $cwl += $wl + 1;
                $lines[$lc] = implode(" ", array_filter([@$lines[$lc], $word]));
            } else {
                $lc += 1;
                $cwl        = 0;
                $lines[$lc] = implode(" ", array_filter([@$lines[$lc], $word]));
            }
        }
        return $lines;
    }

    /**
     * Author OG
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function authorOG($id)
    {
        $author     = Author::findOrFail($id);
        $path       = "assets/images/" . $author->photo;
        $path       = str_replace("redactor/", "redactor/md_", $path);
        $translator = new \MirazMac\BanglaString\Translator\AvroToBijoy\Translator;
        $image      = Image::make(public_path('assets/images/go-pub.jpg'));

        if ($author->photo && file_exists(public_path($path))) {
            $a_image = Image::make(public_path($path))->resize(180, 180);
        } else {
            $a_image = Image::make(public_path('assets/images/def-author.jpg'))->resize(180, 180);
        }
        $image->insert($a_image, 'top-left', 82, 71);

        $image->text(DB::table('books')->where('author_id', $author->id)->count('id'), 206, 505, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(80);
            $font->color('#000000');
            $font->align('left');
            $font->valign('bottom');
        });

        $lines = $this->split_text($author->name_bn, 50);
        if (count($lines) > 1) {
            $lines[0] .= '...';
        }

        $image->text($translator->translate($lines[0]), 320, 71, function ($font) {
            $font->file(public_path('fonts/Li Ador Noirrit ANSI V2.ttf'));
            $font->size(40);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($author->name, 320, 115, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(34);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $lines = $this->split_text($author->bio, 68);

        $l = (count($lines) > 7) ? 7 : count($lines);
        for ($i = 0; $i < $l; $i++) {
            $_line = $translator->translate($lines[$i]);
            if ($i == 6) {$_line .= '...';}
            $offset = 185 + ($i * 30);
            $image->text($_line, 320, $offset, function ($font) {
                $font->file(public_path('fonts/Li Ador Noirrit ANSI V2 Italic.ttf'));
                $font->size(25);
                $font->color('#000000');
            });
        }

        return $image->response('png');
    }

    /**
     * Author OG
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function publisherOG($id)
    {
        $pub        = Publication::findOrFail($id);
        $translator = new \MirazMac\BanglaString\Translator\AvroToBijoy\Translator;
        $image      = Image::make(public_path('assets/images/go-pub.jpg'));

        $path = "assets/images/" . $pub->photo;
        $path = str_replace("redactor/", "redactor/md_", $path);

        if ($pub->photo && file_exists(public_path($path))) {
            $a_image = Image::make(public_path($path))->resize(180, 180);
        } else {
            $a_image = Image::make(public_path('assets/images/def-pub.jpg'))->resize(180, 180);
        }
        $image->insert($a_image, 'top-left', 82, 71);

        $image->text(DB::table('books')->where('publisher_id', $pub->id)->count('id'), 206, 505, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(80);
            $font->color('#000000');
            $font->align('left');
            $font->valign('bottom');
        });

        $lines = $this->split_text($pub->name_bn, 50);
        if (count($lines) > 1) {
            $lines[0] .= '...';
        }

        $image->text($translator->translate($lines[0]), 320, 71, function ($font) {
            $font->file(public_path('fonts/Li Ador Noirrit ANSI V2.ttf'));
            $font->size(40);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($pub->name, 320, 115, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(34);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $lines = $this->split_text($pub->name_bn . ' প্রকাশিত সকল বই দেখুন বইফেরীতে। অনলাইনে বই পড়তে এবং কিনতে আমাদের সাথেই থাকুন। নিজে বই পড়ুন এবং wcÖqRb‡K বই উপহার দিন।', 68);

        $l = (count($lines) > 7) ? 7 : count($lines);
        for ($i = 0; $i < $l; $i++) {
            $_line = $translator->translate($lines[$i]);
            if ($i == 6) {$_line .= '...';}
            $offset = 185 + ($i * 30);
            $image->text($_line, 320, $offset, function ($font) {
                $font->file(public_path('fonts/Li Ador Noirrit ANSI V2 Italic.ttf'));
                $font->size(25);
                $font->color('#000000');
            });
        }

        return $image->response('png');
    }

    /**
     * Author OG
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function categoryOG($id)
    {
        $pub        = Category::findOrFail($id);
        $translator = new \MirazMac\BanglaString\Translator\AvroToBijoy\Translator;
        $image      = Image::make(public_path('assets/images/go-cat.jpg'));

        $image->text(DB::table('books')->where('publisher_id', $pub->id)->count('id'), 206, 505, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(80);
            $font->color('#000000');
            $font->align('left');
            $font->valign('bottom');
        });

        $lines = $this->split_text($pub->name_bn, 50);
        if (count($lines) > 1) {
            $lines[0] .= '...';
        }

        $image->text($translator->translate($lines[0]), 320, 71, function ($font) {
            $font->file(public_path('fonts/Li Ador Noirrit ANSI V2.ttf'));
            $font->size(40);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($pub->name, 320, 115, function ($font) {
            $font->file(public_path('fonts/hind-siliguri-v7-bengali-700.ttf'));
            $font->size(34);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $lines = $this->split_text($pub->name_bn . ' বিষয়ক সকল বই দেখুন বইফেরীতে। অনলাইনে বই পড়তে এবং কিনতে আমাদের সাথেই থাকুন। নিজে বই পড়ুন Ges wcÖqRb‡K বই উপহার দিন।', 68);

        $l = (count($lines) > 7) ? 7 : count($lines);
        for ($i = 0; $i < $l; $i++) {
            $_line = $translator->translate($lines[$i]);
            if ($i == 6) {$_line .= '...';}
            $offset = 185 + ($i * 30);
            $image->text($_line, 320, $offset, function ($font) {
                $font->file(public_path('fonts/Li Ador Noirrit ANSI V2 Italic.ttf'));
                $font->size(25);
                $font->color('#000000');
            });
        }

        return $image->response('png');
    }
}
