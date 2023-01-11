@extends('layouts.books')
@section('title','Checkout')
@php
$seo_title = "Checkout";
$meta_description = '"বইফেরী" অনলাইনে বই কেনা-বেচা এবং পড়ার একটি আদর্শ মার্কেটপ্লেস। বাংলাদেশে সর্বপ্রথম আমরাই দিচ্ছি অনলাইনে জেনুইন "ইবুক" পড়ার সুবিধা, "যত খুশি পড়ুন" স্টাইলে। এবার বই কিনুন এবং পড়ুন নিশ্চিন্তে।';
$keywords = "";
@endphp
@include('page-seo')
@section('content')
@php
$user = Auth::user();
$country = ['Bangladesh'=>'Bangladesh'];
if($user){
    $address = App\Models\Address::where('user_id', $user->id)->orderby('id','desc')->first();
}
$sc = explode(",", request()->ids);
@endphp
<div class="site-content bg-light overflow-hidden " id="content">
    @if($item && count($item->metas)>0)
    <div class="col-full container mb-10">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <article id="post-6" class="post-6 page type-page status-publish hentry">
                    <header class="entry-header space-top-2 space-bottom-1 mb-2">
                        <h4 class="entry-title font-size-7 text-center">{{ __('web.Checkout') }} - {{ date('h:i A') }}</h4>
                    </header>

                    @include('msg')

                    <div class="entry-content">
                       <div class="woocommerce">
                        <div class="woocommerce-info p-4 bg-white border">{{ __('web.Have a coupon?') }}
                            <a class="showcoupon collapsed" data-toggle="collapse" href="#couponBox" role="button" aria-expanded="false" aria-controls="couponBox">
                                {{ __('web.Click here to enter your code') }}
                            </a>
                        </div>
                        <div id="couponBox" class="checkout_coupon mt-4 p-4 bg-white border collapse">
                            <div class="row d-flex">
                                <p class="col-md-4 d-inline form-row form-row-first mb-md-0">
                                    <input type="text" name="coupon_code" class="input-text form-control" placeholder="{{ __('web.Coupon code') }}" id="coupon_code" value="">
                                </p>
                                <p class="col-md-3 d-inline form-row form-row-last">
                                    <input type="button" onclick="applyCoupon();" class="button form-control border-0 height-4 btn btn-dark rounded-0" name="apply_coupon" value="{{ __('web.Apply coupon') }}">
                                </p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        @guest
                        <div class="woocommerce-info mt-3 p-4 bg-white border">{{ __('web.Have an Account?') }}
                            <a class="showcoupon collapsed" data-toggle="collapse" href="#signIn" role="button" aria-expanded="false" aria-controls="signIn">
                                {{ __('web.Click here to Sign In') }}
                            </a>
                        </div>
                        <form id="signIn" class="checkout_coupon mt-4 p-4 bg-white border collapse" action="{{ route('login') }}" method="POST">
                            @csrf
                            {{ session(['redirect',url('checkout')])}}
                            <div class="row d-flex">
                                <p class="col-md-3 d-inline form-row form-row-first mb-md-0">
                                    <input type="email" class="input-text form-control" placeholder="{{ __('web.Email Address') }}" id="email1" name="email" required>
                                </p>
                                <p class="col-md-3 d-inline form-row mb-md-0">
                                    <input type="password" class="input-text form-control" placeholder="{{ __('web.Password') }}" id="password" name="password" required>
                                </p>
                                <p class="col-md-2 d-inline form-row form-row-last">
                                    <input type="submit" class="button form-control border-0 height-4 btn btn-dark rounded-0" value="Sign In">
                                </p>
                            </div>
                            <div class="clear"></div>
                        </form>
                        @endguest

                        <form name="checkout" method="post" class="checkout woocommerce-checkout row mt-3" action="{{ url('checkout') }}">
                            @csrf
                            <div class="col2-set col-md-6 col-lg-7 col-xl-8 mb-6 mb-md-0" id="customer_details">
                                <div class="px-4 pt-5 bg-white border">
                                    <div class="woocommerce-billing-fields">
                                        <h3 class="mb-4 font-size-3">{{ __('web.Billing details') }}</h3>
                                        <div class="woocommerce-billing-fields__field-wrapper row">

                                            <x-form::input column="12" name="name" title="{{ __('web.Full Name') }}" :required="true" type="text" value="{{ @$user->name }}" />
                                            <x-form::input column="6" name="email" title="{{ __('web.Email') }}" :required="true" type="text" value="{{ @$user->email }}" />
                                            <x-form::input column="6" name="mobile" title="{{ __('web.Mobile') }}" :required="true" type="tel" value="{{ @$user->mobile }}" />
                                            <x-form::input column="12" name="bill[street]" title="{{ __('web.Address') }}" :required="true" type="text" value="{{ @$address->street }}" />

                                            <x-form::select column="6" name="bill[country]" title="{{ __('web.Country') }}" :required="true" type="text" value="" :options="$country" value="Bangladesh"/>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('web.District') }} <sup style="color:red;">*</sup></label>
                                                    <select name="bill[district]" id="bill_district" class="form-control" required>
                                                        <option value="">{{ __('web.Select District') }}</option>
                                                        @foreach(['Barguna','Barisal','Bhola','Jhalokati','Patuakhali','Pirojpur','Bandarban','Brahmanbaria','Chandpur','Chittagong','Comilla','Cox\'s Bazar','Feni','Khagrachhari','Lakshmipur','Noakhali','Rangamati','Dhaka','Faridpur','Gazipur','Gopalganj','Kishoreganj','Madaripur','Manikganj','Munshiganj','Narayanganj','Narsingdi','Rajbari','Shariatpur','Tangail','Bagerhat','Chuadanga','Jessore','Jhenaidah','Khulna','Kushtia','Magura','Meherpur','Narail','Satkhira','Jamalpur','Mymensingh','Netrokona','Sherpur','Bogra','Joypurhat','Naogaon','Natore','Chapai Nawabganj','Pabna','Rajshahi','Sirajganj','Dinajpur','Gaibandha','Kurigram','Lalmonirhat','Nilphamari','Panchagarh','Rangpur','Thakurgaon','Habiganj','Moulvibazar','Sunamganj','Sylhet'] as $i)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <x-form::input column="6" name="bill[city]" title="{{ __('web.City') }}" :required="true" type="text" value="{{ @$address->city }}" />
                                            <x-form::input column="6" name="bill[postcode]" title="{{ __('web.Postcode / ZIP') }}" :required="false" type="number" value="{{ @$address->postcode }}" />

                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 pt-5 bg-white border border-top-0 mt-n-one">
                                    <div class="woocommerce-additional-fields">
                                        <h3 class="mb-4 font-size-3">{{ __('web.Additional information') }}</h3>
                                        <div class="woocommerce-additional-fields__field-wrapper">
                                            <p class="col-12 mb-4d75 px-0 form-row notes" id="order_comments_field" data-priority="">
                                                <label for="note" class="form-label">{{ __('web.Order notes (optional)') }}</label>
                                                <textarea name="note" class="input-text form-control" id="note" placeholder="Notes about your order, e.g. special notes for delivery." rows="8" cols="5"></textarea>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 id="order_review_heading" class="d-none">{{ __('web.Your order') }}</h3>
                            <div id="order_review" class="col-md-6 col-lg-5 col-xl-4 woocommerce-checkout-review-order">
                                <div id="checkoutAccordion" class="border border-gray-900 bg-white mb-5">
                                    <div class="p-4d875 border">
                                        <div id="checkoutHeadingOnee" class="checkout-head">
                                            <a href="#" class="text-dark d-flex align-items-center justify-content-between" data-toggle="collapse" data-target="#checkoutCollapseOnee" aria-expanded="true" aria-controls="checkoutCollapseOnee">
                                                <h3 class="checkout-title mb-0 font-weight-medium font-size-3">{{ __('web.Your order') }}</h3>
                                                <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                                                </svg>
                                                <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <div id="checkoutCollapseOnee" class="mt-4 checkout-content collapse show" aria-labelledby="checkoutHeadingOnee" data-parent="#checkoutAccordion">
                                            <table class="shop_table woocommerce-checkout-review-order-table">
                                                <thead class="d-none">
                                                    <tr>
                                                        <th class="product-name">{{ __('web.Product') }}</th>
                                                        <th class="product-total">{{ __('web.Total') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->metas as $meta)
                                                    <tr class="cart_item">
                                                        <td class="product-name">
                                                           {{ $meta->product->title_bn }}&nbsp; <strong class="product-quantity">× {{ $meta->quantity }}</strong>
                                                        </td>
                                                        <td class="product-total">
                                                            <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> @money($meta->rate * $meta->quantity)</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="p-4d875 border">
                                        <div id="checkoutHeadingOne" class="checkout-head">
                                            <a href="#" class="text-dark d-flex align-items-center justify-content-between" data-toggle="collapse" data-target="#checkoutCollapseOne" aria-expanded="true" aria-controls="checkoutCollapseOne">
                                                <h3 class="checkout-title mb-0 font-weight-medium font-size-3">{{ __('web.Cart Totals') }}</h3>
                                                <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                                                </svg>
                                                <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <div id="checkoutCollapseOne" class="mt-4 checkout-content collapse show" aria-labelledby="checkoutHeadingOne" data-parent="#checkoutAccordion">
                                            <table class="shop_table shop_table_responsive">
                                                <tbody>
                                                    <tr class="checkout-subtotal">
                                                        <th>{{ __('web.Subtotal') }}</th>
                                                        <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $item->total }}</span></td>
                                                    </tr>
                                                    <tr class="order-shipping">
                                                        <th>{{ __('web.Shipping') }}</th>
                                                        <td data-title="Shipping">৳ <span id="fill_shipping">{{ ($item->shipping==0) ? 'Free Shipping' : $item->shipping }}</span></td>
                                                    </tr>
                                                    @if($item->gift_wrap>0)
                                                    <tr class="order-shipping">
                                                        <th>{{ __('web.Gift Wrap') }}</th>
                                                        <td data-title="Shipping">৳ {{ $item->gift_wrap }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($item->coupon_discount>0)
                                                    <tr class="order-shipping">
                                                        <th>{{ __('web.Coupon Discount') }}</th>
                                                        <td data-title="Shipping">৳ {{ $item->coupon_discount }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr class="order-shipping">
                                                        <th colspan="2">
                                                            <div class="input-group">
                                                                <input type="text" name="coupon_code" class="input-text form-control" placeholder="{{ __('web.Coupon code') }}" id="coupon_code1" value="">
                                                                <div class="input-group-append">
                                                                    <input type="button" onclick="applyCoupon($('#coupon_code1').val());" class="button form-control border-0 height-4 btn btn-dark rounded-0" name="apply_coupon" value="কুপন প্রয়োগ করুন">
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="p-4d875 border">
                                        <table class="shop_table shop_table_responsive">
                                           <tbody>
                                            <tr class="order-total">
                                                <th>{{ __('web.Total') }}</th>
                                                <td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> <span id="fill_total">@money($item->total + $item->shipping - $item->coupon_discount + $item->gift_wrap)</span></span></strong> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4d875 border">
                                    <div id="checkoutHeadingThreee" class="checkout-head">
                                        <a href="#" class="text-dark d-flex align-items-center justify-content-between" data-toggle="collapse" data-target="#checkoutCollapseThreee" aria-expanded="true" aria-controls="checkoutCollapseThreee">
                                            <h3 class="checkout-title mb-0 font-weight-medium font-size-3">{{ __('web.Payment') }}</h3>
                                            <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                                                <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                                            </svg>
                                            <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                                <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    <div id="checkoutCollapseThreee" class="mt-4 checkout-content collapse show" aria-labelledby="checkoutHeadingThreee" data-parent="#checkoutAccordion">
                                        <div class="pg-radio">
                                            <input id="cod" name="payment" type="radio" value="cod" required />
                                            <label for="cod">{{ __('web.cod') }}</label>
                                            <div class="desc cod_other" style="display:none;">{{ __('web.cod_desc') }}</div>
                                            <div class="desc cod_dhaka" style="display:none;">{{ __('web.cod1_desc') }}</div>
                                        </div>
                                        <div class="pg-radio">
                                            <input id="bkash" checked name="payment" type="radio" value="bkash" required />
                                            <label for="bkash">{{ __('web.bkash') }}</label>
                                            <div class="desc">{{ __('web.bkash_desc') }}</div>
                                        </div>
                                        <div class="pg-radio">
                                            <input id="nagad" checked name="payment" type="radio" value="nagad" required />
                                            <label for="nagad">{{ __('web.nagad') }}</label>
                                            <div class="desc">{{ __('web.nagad_desc') }}</div>
                                        </div>
                                        <div class="pg-radio">
                                            <input id="ssl" name="payment" type="radio" value="sslcommerz" required />
                                            <label for="ssl">{{ __('web.ssl') }}</label>
                                            <div class="desc">{{ __('web.ssl_desc') }}</div>
                                        </div>

                                        <div class="form-check mt-3">
                                            <input type="checkbox" class="form-check-input" id="tos" name="tos" required>
                                            <label class="form-check-label tos_l" for="tos">{!! __('web.tos', ['tos'=>url('legal/terms-of-service'),'pp'=>url('legal/privacy-policy'),'rp'=>url('legal/refund-policy')]) !!}</label>
                                        </div>

                                        <input type="hidden" name="inv" value="{{ $item->id }}">
                                        <input type="hidden" name="ids" value="{{ request()->ids }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row place-order">
                                <button class="button alt btn btn-dark btn-block rounded-0 py-4" type="submit">{{ __('web.Place order') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </article>

    </main>

</div>

</div>
    @else
    <div class="container">
        <header class="entry-header space-top-3 space-bottom-3 mb-2 text-center">
            <img src="{{ asset('assets/images/empty-cart.png') }}" alt="">
        </header>
    </div>
    @endif
</div>
@endsection

@push('_scripts')
window.ck_inv = {{ @$item->id }};
$("#bill_district").on('change', updateShipping);
$("input[name=payment]").on('change', updateShipping);
$(document).ready(function() {
    $("#bill_district").val("{{ @$address->district }}").change();         
});
$(document).ready(function() {
    @if(old('bill.street')) $("#bill_street").val("{{ old('bill.street') }}"); @endif
    @if(old('bill.country')) $("#bill_country").val("{{ old('bill.country') }}").change(); @endif
    @if(old('bill.district')) $("#bill_district").val("{{ old('bill.district') }}").change(); @endif
    @if(old('bill.city')) $("#bill_city").val("{{ old('bill.city') }}"); @endif
    @if(old('bill.postcode')) $("#bill_postcode").val("{{ old('bill.postcode') }}"); @endif
});
@endpush