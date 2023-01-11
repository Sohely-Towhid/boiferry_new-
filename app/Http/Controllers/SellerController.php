<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Product;
use App\Models\VendorInvoice;
use Auth;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $user              = Auth::user();
        $order['pending']  = VendorInvoice::where('status', 2)->where('vendor_id', $user->vendor_id)->count('id');
        $order['complete'] = VendorInvoice::where('status', 4)->where('vendor_id', $user->vendor_id)->count('id');
        $order['shipping'] = VendorInvoice::where('status', 3)->where('vendor_id', $user->vendor_id)->count('id');
        $order['packed']   = VendorInvoice::where('status', 7)->where('vendor_id', $user->vendor_id)->count('id');
        $stock['book']     = Book::where('status', 1)->where('stock', '<=', 0)->where('vendor_id', $user->vendor_id)->count('id');
        $stock['product']  = Product::where('status', 1)->where('stock', '<=', 0)->where('vendor_id', $user->vendor_id)->count('id');
        // dd($order);
        return view('seller.dashboard')->with('order', $order)->with('stock', $stock);
    }

    public function search(Request $request)
    {
        return view('seller.search');
    }

    public function setting(Request $request)
    {
        return view('seller.setting');
    }

    /**
     * Save Image
     * @param  [type] $image [description]
     * @param  array  $input [description]
     * @param  array  $size  [description]
     * @return [type]        [description]
     */
    public function saveImage($image, $size = [])
    {
        $path    = $image->store('redactor', 'redactor');
        $lg      = Image::make(public_path('assets/images/' . $path))->resize($size[0], $size[1]);
        $lg_path = public_path('assets/images/' . str_replace("redactor/", "redactor/lg_", $path));
        $lg->save($lg_path, 100);
        return $path;
    }

    public function SaveSetting(Request $request)
    {
        $user = Auth::user();
        if ($request->mobile || $request->address) {
            $request->validate([
                'mobile'  => 'required|mobile',
                'address' => 'required',
            ]);
            $user->vendor->mobile  = $request->mobile;
            $user->vendor->address = $request->address;
            $user->vendor->save();
        }

        if ($request->images = 'yes') {
            $logo = (@$user->vendor->logos[0]) ? "nullable|image" : 'required|image';

            $request->validate([
                'logo'     => $logo,
                'banner_1' => 'nullable|image',
                'banner_2' => 'nullable|image',
                'banner_3' => 'nullable|image',
            ]);

            if ($request->logo) {
                $user->vendor->logos = [$this->saveImage($request->logo, [500, 500]), $this->saveImage($request->logo, [300, 300])];
            }

            $banners = $user->vendor->banners;

            if ($request->banner_1) {
                $banners[0]            = $this->saveImage($request->banner_1, [800, 420]);
                $user->vendor->banners = $banners;
            }

            if ($request->banner_2) {
                $banners[1]            = $this->saveImage($request->banner_2, [800, 420]);
                $user->vendor->banners = $banners;
            }

            if ($request->banner_3) {
                $banners[2]            = $this->saveImage($request->banner_3, [800, 420]);
                $user->vendor->banners = $banners;
            }

            $user->vendor->save();
        }

        if ($request->password_change == 'yes') {
            $request->validate([
                'old_password'          => 'required|current_password:web',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required',
            ]);

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            return redirect("setting")->with('success', 'Password Update Successfull.');
        }

        if ($request->banks == 'yes') {

            $request->validate([
                'bank.bank'   => 'required',
                'bank.name'   => 'required',
                'bank.number' => 'required',
                'bank.branch' => 'required',
            ]);

            $details                     = $user->vendor->details;
            $details['bank']             = $request->bank;
            $details['bank']['verified'] = false;
            $user->vendor->details       = $details;
            $user->vendor->save();
        }

        return redirect("setting")->with('success', 'Details Updated Successfully.');

    }
}
