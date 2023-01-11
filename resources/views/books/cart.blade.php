@extends('layouts.books')
@section('title','Cart')
@php
$seo_title = "Cart";
$meta_description = '"বইফেরী" অনলাইনে বই কেনা-বেচা এবং পড়ার একটি আদর্শ মার্কেটপ্লেস। বাংলাদেশে সর্বপ্রথম আমরাই দিচ্ছি অনলাইনে জেনুইন "ইবুক" পড়ার সুবিধা, "যত খুশি পড়ুন" স্টাইলে। এবার বই কিনুন এবং পড়ুন নিশ্চিন্তে।';
$keywords = "";
@endphp
@include('page-seo')
@section('content')
@php $locale = (App::currentLocale()=='en') ? '' : '_bn'; @endphp
<div class="site-content bg-light overflow-hidden " id="content">
    @if($item && count($item->metas)>0)
    <div class="container">
        <header class="entry-header space-top-2 space-bottom-1 mb-2 text-center">
            <h1 class="entry-title font-size-7">{{ __('web.Your cart') }}: {{ count($item->metas) }} items</h1>
        </header>
        <div class="row pb-8">
            
            <div id="primary" class="col-md-8 content-area">
                <main id="main" class="site-main ">
                    <div class="page type-page status-publish hentry">

                        <div class="entry-content">
                            <div class="woocommerce">
                                <div class="woocommerce-cart-form table-responsive">
                                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                                        <thead>
                                            <tr>
                                                <th class="chk_td"><input type="checkbox" class="form-check-input" id="all_cart" name="all_cart"></th>
                                                <th class="product-name">{{ __('web.Book') }}</th>
                                                <th class="product-price">{{ __('web.Price') }}</th>
                                                <th class="product-quantity">{{ __('web.Quantity') }}</th>
                                                <th class="product-subtotal">{{ __('web.Total') }}</th>
                                                <th class="product-remove">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tr_sel">
                                            @foreach($item->metas as $meta)
                                            <tr class="woocommerce-cart-form__cart-item cart_item">
                                                <td class="chk_td"><input type="checkbox" class="form-check-input" id="cart_{{ $meta->id }}" name="cart[]" value="{{ $meta->id }}"></td>
                                                <td class="product-name" data-title="Product">
                                                    <div class="d-flex align-items-center">
                                                        <a href="#">
                                                            <img src="{{ showImage(@$meta->product->images[0],'xs') }}" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" alt="">
                                                        </a>
                                                        <div class="ml-3 m-w-200-lg-down">
                                                            <a href="#">{{ $meta->product->{'title'.$locale} }} @if(@$meta->product->pre_order) {{ __('web.pre_order') }} @endif</a>
                                                            <a href="#" class="text-gray-700 font-size-2 d-block" tabindex="0">{{ @$meta->product->{'author'.$locale} }}</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="product-price" data-title="Price">
                                                    <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> {{ e2b($meta->rate) }}</span>
                                                </td>
                                                <td class="product-quantity" data-title="Quantity">
                                                    <div class="quantity d-flex align-items-center">

                                                        <div class="border px-3 width-120">
                                                            <div class="js-quantity">
                                                                <div class="d-flex align-items-center">
                                                                   <label class="screen-reader-text sr-only">{{ __('web.Quantity') }}</label>
                                                                   <a class="js-minus text-dark" href="javascript:;" onclick="updateCart({{ $meta->book_id }}, {{ $meta->quantity - 1 }}, true);">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="1px">
                                                                        <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M-0.000,-0.000 L10.000,-0.000 L10.000,1.000 L-0.000,1.000 L-0.000,-0.000 Z"></path>
                                                                    </svg>
                                                                </a>
                                                                <input type="number" onchange="updateCart({{ $meta->book_id }}, $(this).val(), true);" class="input-text qty text js-result form-control text-center border-0" step="1" min="1" max="100" name="quantity" value="{{ $meta->quantity }}" title="Qty">
                                                                <a class="js-plus text-dark" href="javascript:;" onclick="updateCart({{ $meta->book_id }}, {{ $meta->quantity + 1 }}, true);">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px">
                                                                        <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M10.000,5.000 L6.000,5.000 L6.000,10.000 L5.000,10.000 L5.000,5.000 L-0.000,5.000 L-0.000,4.000 L5.000,4.000 L5.000,-0.000 L6.000,-0.000 L6.000,4.000 L10.000,4.000 L10.000,5.000 Z"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    </div>
                                                </td>
                                                <td class="product-subtotal" data-title="Total">
                                                    <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> {{  e2b(number_format((float)$meta->quantity * $meta->rate, 2, '.', '')) }}</span>
                                                </td>
                                                <td class="product-remove">
                                                    <a href="javascript:{}" onclick="rem4mcart({{ $meta->book_id }}, true);" class="remove" aria-label="Remove this item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                                            <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.011,13.899 L13.899,15.012 L7.500,8.613 L1.101,15.012 L-0.012,13.899 L6.387,7.500 L-0.012,1.101 L1.101,-0.012 L7.500,6.387 L13.899,-0.012 L15.011,1.101 L8.613,7.500 L15.011,13.899 Z"></path>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>

            <div id="secondary" class="col-md-4 cart-page-sidebar cart-collaterals order-1" role="complementary">
                <div id="cartAccordion" class="border border-gray-900 bg-white mb-5">
                    <div class="p-4d875 border">
                        <div id="cartHeadingOne" class="cart-head">
                            <a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#cartCollapseOne" aria-expanded="true" aria-controls="cartCollapseOne">
                                <h3 class="cart-title mb-0 font-weight-medium font-size-3">{{ __('web.Cart Totals') }}</h3>
                                <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                                </svg>
                                <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                                </svg>
                            </a>
                        </div>
                        <div id="cartCollapseOne" class="mt-4 cart-content collapse show" aria-labelledby="cartHeadingOne" data-parent="#cartAccordion">
                            <table class="shop_table shop_table_responsive">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>{{ __('web.Subtotal') }}</th>
                                        <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> <span id="cart_sub_total">{{ $item->total }}</span></span></td>
                                    </tr>
                                    <tr class="order-shipping">
                                        <th>{{ __('web.Shipping') }}</th>
                                        <td data-title="Shipping"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $item->shipping }}</td>
                                    </tr>
                                    <tr class="order-shipping">
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="yes" id="gift_wrap" {{ ($item->gift_wrap>0)? 'checked' : '' }}>
                                                <label class="form-check-label" for="gift_wrap">{{ __('web.Gift Wrap') }} (৳ {{ App\Models\Setting::getValue('book_home_gift_wrap') }})</label>
                                            </div>
                                        </th>
                                        <td data-title="Shipping"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $item->gift_wrap }}</td>
                                    </tr>
                                    @if($item->coupon_discount>0)
                                    <tr class="order-shipping">
                                        <th>{{ __('web.Coupon Discount') }}</th>
                                        <td data-title="Discount"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $item->coupon_discount }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="p-4d875 border">
                        <div id="cartHeadingThree" class="cart-head">
                            <a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#cartCollapseThree" aria-expanded="true" aria-controls="cartCollapseThree">
                                <h3 class="cart-title mb-0 font-weight-medium font-size-3">{{ __('web.Coupon') }}</h3>
                                <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                                </svg>
                                <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                                </svg>
                            </a>
                        </div>
                        <div id="cartCollapseThree" class="mt-4 cart-content collapse show" aria-labelledby="cartHeadingThree" data-parent="#cartAccordion">
                            <div class="coupon">
                                <label for="coupon_code">{{ __('web.Coupon') }}:</label>
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="{{ __('web.Coupon code') }}" autocomplete="off">
                                <input type="button" class="button" name="apply_coupon" onclick="applyCoupon();" value="{{ __('web.Apply coupon') }}">
                            </div>
                        </div>
                    </div>
                    <div class="p-4d875 border">
                        <table class="shop_table shop_table_responsive">
                            <tbody>
                                <tr class="order-total">
                                    <th>{{ __('web.Total') }}</th>
                                    <td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> <span id="cart_total">@money($item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount)</span></span></strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="wc-proceed-to-checkout">
                    <a href="{{ url('checkout') }}" class="checkout-button button alt wc-forward btn btn-dark btn-block rounded-0 py-4">{{ __('web.Proceed to checkout') }}</a>
                </div>
            </div>
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

@push('scripts')
<script>
    $("#gift_wrap").on('change', giftWrap);

    function checkAll(mode){
        $(".tr_sel input:checkbox").prop('checked',mode);
    }
    $("#all_cart").on('change', function(){
        if($(this).prop('checked')){
            checkAll(true);
        }else{
            checkAll(false);
        }
    });
    $("#all_cart").prop('checked',true).change();

    $(".tr_sel input:checkbox").on('change', checkOutCalculator);
    @if($item)
    function checkOutCalculator(){
        var items = {!! json_encode(collect(@$item->metas)->mapWithKeys(function($item, $key){
            return [$item['id'] => ['id'=>$item['id'], 'quantity' => $item['quantity'], 'rate' => $item['rate']]];
        })) !!};
        var total = 0;
        var ids = [];
        $(".tr_sel input:checkbox").each(function(){
            if($(this).prop('checked')){
                var id = $(this).val();
                ids.push(id);
                total += Number(Number(items[id].rate) * Number(items[id].quantity));
            }
        });
        $("#cart_sub_total").html(total.toFixed(2));
        total = total - Number({{ $item->coupon_discount }}) + Number({{ $item->gift_wrap }}) + Number({{ $item->shipping }});
        total = total.toFixed(2);
        $("#cart_total").html(total);
        $(".checkout-button").attr('href', '{{ url('checkout') }}?ids=' + ids.join(','));
        console.log(ids);
    }
    checkOutCalculator();
    @endif

</script>
@endpush