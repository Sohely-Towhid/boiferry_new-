@extends('layouts.books')
@section('title',__('web.Wishlist'))
@section('content')
@php 
$user = Auth::user();
$items = App\Models\Wishlist::with(['book','book._author'])->where('user_id', $user->id)->where('book_id','>',0)->get();
@endphp
<main id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3 border-right">
                <h6 class="font-weight-medium font-size-7 pt-5 pt-lg-8  mb-5 mb-lg-7">{{ __('web.My Account') }}</h6>
                <div class="tab-wrapper">
                    @include('account.menu')
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
                        <div class="pt-5 pt-lg-8 pl-md-5 pl-lg-9 space-bottom-2 space-bottom-lg-3 mb-xl-1">
                            <h6 class="font-weight-medium font-size-7 ml-lg-1 mb-lg-8 pb-xl-1">{{ __('web.Wishlist') }}</h6>
                            
                            @if(count($items)>0)
                            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                                <thead>
                                    <tr>
                                        <th class="product-name">{{ __('web.Book') }}</th>
                                        <th class="product-price">{{ __('web.Price') }}</th>
                                        <th class="product-quantity">{{ __('web.Sale') }}</th>
                                        <th class="product-remove">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $book)
                                    <tr class="woocommerce-cart-form__cart-item cart_item">
                                        <td class="product-name" data-title="Product">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ url('book/'.$book->book->slug) }}">
                                                    <img src="{{ showImage(@$book->book->images[0],'xs') }}" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" alt="">
                                                </a>
                                                <div class="ml-3 m-w-200-lg-down">
                                                    <a href="{{ url('book/'.$book->book->slug) }}">{{ $book->book->title_bn }}</a>
                                                    <a href="{{ url('author/'.@$book->book->author->slug) }}" class="text-gray-700 font-size-2 d-block" tabindex="0">{{ $book->book->author_bn }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-price" data-title="Price">
                                            <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $book->book->rate }}</span>
                                        </td>
                                        <td class="product-subtotal" data-title="Total">
                                            <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span> {{ $book->book->sale }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <a href="javascript:{}" onclick="add2cart({{ $book->book->id }}, 1, 'my-account/wishlist');" class="remove" aria-label="{{ __('web.Add To Cart') }}">
                                               {{ __('web.Add to cart') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center">
                                <img src="{{ asset('assets/images/icn_no_wishlist.png') }}" width="60%" alt="">
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection