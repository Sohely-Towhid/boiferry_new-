<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Cache;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class SettingController extends Controller
{

    public function booksSetting(Request $request)
    {
        $items = Setting::where('name', 'like', 'book%')->get()->keyBy(function ($item) {
            return $item->name;
        });
        return view('setting.books')
            ->with('items', json_decode(json_encode($items)));
    }

    /**
     * Save Book Site Setting
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postbooksSetting(Request $request)
    {
        Setting::where('name', 'book_home_slider')->update(['value' => $request->book_home_slider]);
        Setting::where('name', 'book_home_shipping')->update(['value' => $request->book_home_shipping]);
        Setting::where('name', 'book_home_shipping_cod')->update(['value' => $request->book_home_shipping_cod]);
        Setting::where('name', 'book_home_shipping_out')->update(['value' => $request->book_home_shipping_out]);
        Setting::where('name', 'book_home_shipping_out_cod')->update(['value' => $request->book_home_shipping_out_cod]);
        Setting::where('name', 'book_home_free_shipping')->update(['value' => $request->book_home_free_shipping]);
        Setting::where('name', 'book_home_gift_wrap')->update(['value' => $request->book_home_gift_wrap]);
        Setting::where('name', 'book_home_extra_discount')->update(['value' => $request->book_home_extra_discount]);

        // top ad save
        $old_ad           = Setting::where('name', 'book_home_top_ad')->first();
        $top_ad['status'] = (@$request->book_home_top_ad['status'] == 'on') ? 1 : 0;
        $top_ad['link']   = $request->book_home_top_ad['link'];
        if (@$request->book_home_top_ad['image']) {
            $image   = $request->book_home_top_ad['image'];
            $path    = $image->store('redactor', 'redactor');
            $ext     = $image->extension();
            $lg      = Image::make(public_path('assets/images/' . $path));
            $lg_path = public_path('assets/images/' . str_replace([$ext], ['webp'], $path));
            $lg->encode('webp', 80)->save($lg_path, 80);
            $image           = str_replace([$ext], ['webp'], $path);
            $top_ad['image'] = $image;
        } else {
            $top_ad['image'] = @$old_ad->value->image;
        }
        Setting::where('name', 'book_home_top_ad')->update(['value' => $top_ad]);

        for ($i = 1; $i <= 20; $i++) {
            $data['book_home_block_' . $i] = ['status' => (@$request->status[$i]) ? 1 : 0, 'value' => [
                'take_items'   => @$request->take_items[$i],
                'show_items'   => @$request->show_items[$i],
                'theme'        => @$request->theme[$i],
                'bg'           => @$request->bg[$i],
                'title'        => @$request->title[$i],
                'category'     => @$request->category[$i],
                'author'       => @$request->author[$i],
                'opt_category' => @$request->opt_category[$i],
            ]];
            Setting::where('name', 'book_home_block_' . $i)->update($data['book_home_block_' . $i]);
        }

        for ($i = 1; $i <= 6; $i++) {
            $fixed_data['book_home_fixed_' . $i] = ['status' => (@$request->fixed_status[$i]) ? 1 : 0, 'value' => [
                'name'        => @$request->name[$i],
                'position'    => @$request->position[$i],
                'data_type'   => @$request->data_type[$i],
                'time_period' => @$request->time_period[$i],
                'bg'          => @$request->fixed_bg[$i],
            ]];
            Setting::where('name', 'book_home_fixed_' . $i)->update($fixed_data['book_home_fixed_' . $i]);
        }

        Cache::forever('book_setting', false);
        return redirect('setting/books')->with('success', 'Setting updated Successfully!');
    }

    public function booksSubscription(Request $request)
    {
        $items = Setting::where('name', 'like', 'subscription%')->get()->keyBy(function ($item) {
            return $item->name;
        });
        return view('setting.subscription')->with('items', json_decode(json_encode($items)));
    }

    /**
     * Save Subscription Setting
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postbooksSubscription(Request $request)
    {
        $request->validate([
            'subscription_1'  => 'required|numeric',
            'subscription_3'  => 'required|numeric',
            'subscription_6'  => 'required|numeric',
            'subscription_12' => 'required|numeric',
        ]);

        Setting::where('name', 'subscription_1')->update(['value' => $request->subscription_1]);
        Setting::where('name', 'subscription_3')->update(['value' => $request->subscription_3]);
        Setting::where('name', 'subscription_6')->update(['value' => $request->subscription_6]);
        Setting::where('name', 'subscription_12')->update(['value' => $request->subscription_12]);

        return redirect('setting/subscription')->with('success', 'Setting updated Successfully!');
    }

    public function booksFeature(Request $request)
    {
        $items = Setting::where('name', 'like', 'feature%')->get()->keyBy(function ($item) {
            return $item->name;
        });
        return view('setting.feature')->with('items', json_decode(json_encode($items)));
    }

    /**
     * Home Page Features
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postbooksFeature(Request $request)
    {
        for ($i = 1; $i <= 3; $i++) {
            $setting = Setting::where('name', 'feature_' . $i)->first();
            $image   = @$request->image[$i];
            if ($image) {
                $path    = $image->store('redactor', 'redactor');
                $ext     = $image->extension();
                $lg      = Image::make(public_path('assets/images/' . $path))->resize(431, 192);
                $lg_path = public_path('assets/images/' . str_replace([$ext], ['webp'], $path));
                $lg->encode('webp', 80)->save($lg_path, 80);
                $image = str_replace([$ext], ['webp'], $path);
                if (!preg_match('/webp$/', $path)) {
                    @unlink(public_path('assets/images/' . $path));
                }
            } else {
                $image = $setting->value->image;
            }
            $data['feature_' . $i] = ['status' => (@$request->status[$i] == 'on') ? 1 : 0, 'value' => [
                'bg'    => @$request->bg[$i],
                'image' => $image,
                'link'  => @$request->link[$i],
            ]];
            $setting->update($data['feature_' . $i]);
        }
        Cache::forever('feature_setting', false);
        return redirect('setting/feature')->with('success', 'Setting updated Successfully!');
    }

}
