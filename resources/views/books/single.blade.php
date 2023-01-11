@extends('layouts.books')
@php $locale = (App::currentLocale()=='en') ? '' : '_bn'; @endphp
@section('title',$item->{'title'.$locale})
@include('web-seo',['item', $item])
@section('content')
@php
$setting = loadSetting('book', true);
$user = Auth::user();
if($user){
    $review = App\Models\Review::where('book_id', $item->id)->where('user_id', $user->id)->where('status', 0)->first();
}else{
    $review = false;
}
$reviews = App\Models\Review::where('book_id', $item->id)->where('status',2)->paginate(5);
$ppp = _percent($item->rate, $item->sale,"% ".__('web.off'));
$preview = (preg_match("/preview/",$item->preview)) ? $item->preview : false;
if($preview){
    $preview = explode("|", $preview);
}

$book_home_extra_discount = $setting['book_home_extra_discount'];
$_extra_discount = ['bkash'=>0, 'nagad'=>0, 'ssl'=> 0];
$_t_d = 0;
foreach($book_home_extra_discount->value as $ed_key => $_e_d){
    $_t_d += $_e_d;
    $_extra_discount[$ed_key] = ($_e_d==0) ? 0 :  ($item->sale * ($_e_d/100));
}
$p_d = $item->rate - $item->sale;
@endphp
<style>
    #pdf_preview{ padding: 0; }
    .pdfobject-container { height: 80vh; border: 0; }
    .vp{background: #45b44d;padding-left: 8px;padding-right: 8px;font-size: 14px;line-height: 24px;border-radius: 5px;color: white;}
    blockquote {border-left: 3px solid #00CC8F;color: #1a1a1a;font-family: Georgia, Times, "Times New Roman", serif;font-size: 1.25em;font-style: italic;line-height: 1.8em;margin-left: 1em;padding: 1em 2em;position: relative;transition: 0.2s border ease-in-out;z-index: 0;}
    blockquote:before {content: "";position: absolute;top: 50%;left: -4px;height: 2em;background-color: #fff;width: 5px;margin-top: -1em;}
    blockquote:after {content: "\f10d";position: absolute;top: 50%;left: -0.5em;color: #00CC8F;font-family: 'Font Awesome 5 Pro';font-weight: 400;line-height: 1em;text-align: center;text-indent: -2px;width: 1em;margin-top: -0.5em;transition: 0.2s all ease-in-out, 0.4s transform ease-in-out;}
    .mobile-fixed-bottom{
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #e6e6e6;
        text-align: right;
        z-index: 150;
        padding:  5px;
    }
    .mobile-fixed-bottom button{
        margin-bottom: 0px !important;
    }
    .discount-card{ background: blue; height: 85px; color: white; text-align: center; border: 3px solid white; width: 100%;}
    .discount-card::before{
        position: absolute;
        content: "";
        height: 20px;
        left: -10px;
        border-radius: 50%;
        z-index: 1;
        top: 32px;
        background-color: #ffffff;
        width: 20px;
    }
    .discount-card::after{
        position: absolute;
        content: "";
        height: 20px;
        right: -10px;
        border-radius: 50%;
        z-index: 1;
        top: 32px;
        background-color: #ffffff;
        width: 20px;
    }
    .bkash{
        background-color: #e2126e;
    }.nagad{
        background-color: #f7941d;
    }.ssl{
        background-color: #295ca9;
    }
    .discount-card>div{
        background:  white;
        margin-top: 8px;
        border-radius: 8px;
        margin-left: 20px;
        margin-right: 20px;
        height: 35px;
        color:  black;
        text-align: center;
        line-height: 35px;
        font-size: 26px;
        font-weight: bold;
    }
    .discount-card img{height: 28px;margin: auto;padding-top: 6px;}
    .product_type{
        color: #8a8a8d;
        font-size: 25px;
    }
    .btn-read{ background: #6fa900; color: white;}
    .btn-read:hover{ background: #598505; color: white;}
    .btn-a2c{ background: #3366cc; color: white;}
    .btn-a2c:hover{ background: #2a57b1; color: white;}

     .img-thumbnail {
            border-radius: 33px;
            width: 61px;
            height: 61px;
        }
.smd {
            width: 200px;
            font-size: small;
            text-align: center;
        }
 .cpy {
            border: none;
            background-color: #e6e2e2;
            border-bottom-right-radius: 4px;
            border-top-right-radius: 4px;
            cursor: pointer;
        }
        .fass:before {
            position: relative;
            top: 13px;
        }
</style>

<main id="main" class="site-main ">
    <div class="product">
        <div class="bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 pt-8">
                        @include('msg')
                    </div>
                    <div class="col-md-3 col-wd-3 woocommerce-product-gallery woocommerce-product-gallery--with-images images">
                        <figure class="woocommerce-product-gallery__wrapper mb-0">
                            @if($ppp)
                            <span class="ribbon1"><div class="discount">{{ $ppp }}</div></span>
                            @endif
                            <div class="js-slick-carousel u-slick" data-pagi-classes="text-center u-slick__pagination my-4">
                                @foreach($item->images as $__key => $img)
                                <div class="js-slide">
                                    <img src="{{ showImage($img,'md') }}" alt="{{ $item->{'title'.$locale} }}" id="img_{{ $__key }}" class="mx-auto img-fluid">
                                </div>
                                @endforeach
                            </div>
                        </figure>
                        <div class="text-center">
                            <span class="text-yellow-darker">
                                @if(@$item->rating_review->rating)
                                @for($i=1; $i<=@$item->rating_review->rating; $i++)
                                <span class="fas fa-star"></span>
                                @endfor
                                @else
                                {{ __('web.No Rating Yet!') }}
                                @endif
                            </span>
                            <span class="ml-3">({{ $item->rating_review->rating_total }})</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-wd-6 pl-0 summary entry-summary">
                        <div class="px-4 px-xl-5 px-wd-7 pb-5">
                            <h1 class="product_title entry-title font-size-7 mb-1">
                                {{ $item->{'title'.$locale} }}
                                <span style="font-size: 15px;">{{ '('.basicTrns($item->type).")" }}</span>
                            </h1>
                            <p>{{ $item->short_description }}</p>
                            @if($item->type)
                            <h2 class="product_type entry-title font-size-7 mb-1"></h2>
                            @endif
                            <div class="font-size-2 mb-1 mt-1">
                                <span class="font-weight-medium">
                                    {{ __('web.Author') }}:
                                </span>
                                <span class="ml-1 text-gray-600"><a href="{{ url('author/'.@$item->_author->slug) }}">{{ $item->{'author'.$locale} }}</a></span>
                            </div>
                            <p>
                                @php $others = []; @endphp
                                @if($item->others)
                                @foreach($item->others as $other)
                                @php 
                                $ex_author = $other->{'name'.$locale};
                                $others[] = __('web.'.$other->type).': <a href="'. url('author',$other->slug) .'">'.$ex_author."</a>"; 
                                @endphp
                                @endforeach
                                {!! @implode(', ',$others) !!}
                                @endif
                            </p>
                            <div class="mb-2 font-size-3 mb-1">
                                <span class="font-weight-medium">{{ __('web.Subject') }}: </span>
                                <span class="ml-2 text-gray-600"><a href="{{ url('category/'.$item->category->slug) }}">{{ $item->category->{'name'.$locale} }}</a></span>
                            </div>
                            <div class="mb-2 font-size-3 mb-1">
                                <span class="font-weight-medium">{{ __('web.Stock') }}: </span>
                                <span class="ml-2 text-gray-600">
                                    @if($item->actual_stock>0)
                                    <i class="fa fa-check-circle text-success"></i> {{ __('web.stock_text', ["qnt"=> e2b(round($item->actual_stock,10))]) }}
                                    {{-- @else --}}
                                    {{-- <i class="fa fa-times-circle text-danger"></i> <span class="text-danger">{{ __('web.stock_out') }}</span> --}}
                                    @endif
                                </span>
                            </div>

                            <p class="price font-size-22 font-weight-medium mb-3">
                                @if($item->rate > $item->sale)
                                <span class="woocommerce-Price-amount amount pr-4">
                                    <span class="woocommerce-Price-currencySymbol">৳</span> <strike>{{ e2b($item->rate) }}</strike>
                                </span>
                                @endif
                                 <span class="woocommerce-Price-amount amount">
                                    <span class="woocommerce-Price-currencySymbol">৳</span> {{ e2b($item->sale) }}
                                </span>
                            </p>


                            {{-- <div class="mb-2 font-size-3 mb-4">
                                <span class="font-weight-medium">ISBN:</span>
                                <span class="ml-2 text-gray-600">{{ ($item->isbn)? $item->isbn : 'No ISBN' }}</span>
                            </div> --}}

                            @if($_t_d)
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <strong>অনলাইন পেমেন্ট করলে ক্যাশব্যাকে অফার মূল্য</strong>
                                    </div>
                                    <div class="col-6 col-md-4 p-0">
                                        <div class="discount-card bkash">
                                        <div>{{ $item->sale - $_extra_discount['bkash'] }}</div>
                                        <img src="{{ asset('assets/images/discount-bkash.svg') }}" alt="">
                                        </div>
                                        সাস্রয়ঃ ৳ @money($p_d + $_extra_discount['bkash'])
                                    </div>
                                    <div class="col-6 col-md-4 p-0">
                                        <div class="discount-card nagad">
                                        <div>{{ $item->sale - $_extra_discount['nagad'] }}</div>
                                        <img src="{{ asset('assets/images/discount-nagad.svg') }}" alt="">
                                        </div>
                                        সাস্রয়ঃ ৳ @money($p_d + $_extra_discount['nagad'])
                                    </div>
                                    <div class="col-12 col-md-4 p-0">
                                        <div class="discount-card ssl">
                                        <div>{{  $item->sale - $_extra_discount['ssl'] }}</div>
                                        <img src="{{ asset('assets/images/discount-ssl.svg') }}" alt="">
                                        </div>
                                        সাস্রয়ঃ ৳ @money($p_d + $_extra_discount['ssl'])
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="mobile-fixed-bottom flex-wrap d-md-none d-lg-none">
                                <button type="button" class="btn border-1 rounded-0" onclick="add2wishlist({{ $item->id }});"><i class="fal fa-heart"></i></a>
                                @if($item->preview)
                                <button type="button" class="btn btn-read" onclick="showPreview();">{{ __('web.Book Preview') }}</button>
                                @endif
                                @if($item->stock>0)
                                <button type="button" class="btn btn-a2c border-0 rounded-0" onclick="add2cart({{ $item->id }}, 1, false, 'img_0');">{{ __('web.Add to cart') }} @if($item->pre_order) {{ __('web.pre_order') }} @endif</button>
                                @endif
                            </div>

                            <div class="cart d-md-flex align-items-center flex-wrap d-none d-md-block d-lg-block">
                                @if($item->preview)
                                <button type="button" class="mb-4 mb-md-0 btn btn-read border-1 rounded-0 p-3 button alt mr-md-3" onclick="showPreview();">{{ __('web.Book Preview') }}</button>
                                @endif
                                @if($item->status==1)
                                <button onclick="add2cart({{ $item->id }}, 1, false, 'img_0');"type="button" class="mb-4 mb-md-0 btn btn-a2c border-0 rounded-0 p-3 single_add_to_cart_button button alt">{{ __('web.Add to cart') }} @if($item->pre_order) {{ __('web.pre_order') }} @endif</button>
                                @endif
                                @if($item->ebook)
                                <button type="button" class="mb-4 mb-md-0 btn border rounded-0 p-3 button alt ml-md-3">{{ __('web.Read Ebook') }}</button>
                                @endif
                            </div>
                            <div class="d-md-flex align-items-center flex-wrap mt-3">
                                <ul class="list-unstyled nav">
                                    <li class="mr-3 mb-4 mb-md-0">
                                        <a href="javascript:{};" onclick="add2wishlist({{ $item->id }});" class="btn border h-primary"><i class="fal fa-heart mr-2"></i> {{ __('web.Add to Wishlist') }}</a>
                                    </li>
                                    <li class="mr-3 mb-4 mb-md-0">
                                        <a href="javascript:{};" class="btn h-primary border" onclick="$('#modal-share').modal('show');"><i class="fal fa-share-alt mr-2"></i> {{ __('web.Share') }}</a>
                                    </li>
                                </ul>
                            </div>
                            @if(Auth::check())
                            <div class="text-center mt-5 text-danger"><a href="javascript:{};" onclick="$('#report').toggle();"><i class="fa fa-info-circle mr-2"></i>{{ __('web.Report incorrect information') }}</a></div>
                            @else
                            <div class="text-center mt-5 text-danger"><a href="{{ url('login') }}"><i class="fa fa-info-circle mr-2"></i>{{ __('web.Report incorrect information') }}</a></div>
                            @endif
                            <div id="report" class="mt-2 animated fadeIn" style="display: none;">
                                <form action="" method="POST">
                                    @csrf
                                    <textarea name="report" rows="4" class="form-control" placeholder="{{ __('web.report_help') }}" required></textarea>
                                    <button type="submit" class="mt-2 btn btn-info">{{ __('web.Send Report') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-wd-3 d-none d-md-block d-lg-block">
                        @include('books.joined_single_line', ['_title'=> __('web.Bought Together'), '_link'=>'', '_loop'=> $bt, '_show'=> 2, '_bg'=> '', '_rows'=> 2])

                        {{-- single-product-feature-list <ul class="features">
                            <li class="feature m-hide">
                                <div class="feature-inner">
                                    <div class="feature-thumbnail">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="feature-info">
                                        <strong class="feature-title font-size-3">{{ __('web.Cash On Delivery') }}</strong>
                                        <span class="feature-desc">{{ __('web.Pay when you receive product') }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="feature m-hide">
                                <div class="feature-inner">
                                    <div class="feature-thumbnail">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="feature-info">
                                        <strong class="feature-title font-size-3">{{ __('web.Fast Delivery') }}</strong>
                                        <span class="feature-desc">{{ __('web.Receive products in amazing time') }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="feature">
                                <div class="feature-inner">
                                    <div class="feature-thumbnail">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="feature-info">
                                        <strong class="feature-title font-size-3">{{ __('web.Secure Payment') }}</strong>
                                        <span class="feature-desc">{{ __('web.100% Secure Payment') }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="feature m-hide">
                                <div class="feature-inner">
                                    <div class="feature-thumbnail">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div class="feature-info">
                                        <strong class="feature-title font-size-3">{{ __('web.Secure Shopping') }}</strong>
                                        <span class="feature-desc">{{ __('web.Your data is always protected') }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul> --}}

                    </div>
                    <div class="col-md-12">

                        <div class="woocommerce-tabs wc-tabs-wrapper mb-5">
                            <ul class="tabs wc-tabs nav pb-6 justify-content-md-center flex-nowrap flex-md-wrap overflow-auto overflow-md-visble" id="pills-tab" role="tablist">
                                <li class="flex-shrink-0 flex-md-shrink-1 nav-item">
                                    <a class="py-2 nav-link font-weight-medium active" id="summary-tab" data-toggle="pill" href="#summary-pill" role="tab" aria-controls="summary-pill" aria-selected="true">
                                        {{ __('web.Summary') }}
                                    </a>
                                </li>
                                <li class="flex-shrink-0 flex-md-shrink-1 nav-item">
                                    <a class="py-2 nav-link font-weight-medium" id="pd-tab" data-toggle="pill" href="#pd-pill" role="tab" aria-controls="pd-pill" aria-selected="false">
                                        {{ __('web.Product Details') }}
                                    </a>
                                </li>
                                <li class="flex-shrink-0 flex-md-shrink-1 nav-item">
                                    <a class="py-2 nav-link font-weight-medium" id="review-tab" data-toggle="pill" href="#review-pill" role="tab" aria-controls="review-pill" aria-selected="false">
                                        {{ __('web.Reviews') }} ({{ @$item->rating_review->rating_total }})
                                    </a>
                                </li>
                                <li class="flex-shrink-0 flex-md-shrink-1 nav-item">
                                    <a class="py-2 nav-link font-weight-medium" id="author-tab" data-toggle="pill" href="#author-pill" role="tab" aria-controls="author-pill" aria-selected="false">
                                        {{ __('web.Author') }}
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content container" id="pills-tabContent">
                                <div class="woocommerce-Tabs-panel panel col-xl-8 offset-xl-2 entry-content wc-tab tab-pane fade pt-0 show active" id="summary-pill" role="tabpanel" aria-labelledby="summary-tab">
                                    <div class="text-justify" style="font-family: SolaimanLipi;">{!! $item->description !!}</div>

                                    <div class="d-none">
                                        @if(is_array(@$item->seo->keywords))
                                        {{ @implode(',', $item->seo->keywords) }}<br>
                                        @endif
                                        {{ @$item->seo->meta_description }}
                                    </div>
                                </div>

                                <div class="woocommerce-Tabs-panel panel col-xl-8 offset-xl-2 entry-content wc-tab tab-pane fade pt-0" id="pd-pill" role="tabpanel" aria-labelledby="pd-tab">

                                    <div class="table-responsive mb-4">
                                        <table class="table table-hover table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th class="px-4 px-xl-5">{{ __('web.Type') }} </th>
                                                    <td class="">{{ basicTrns($item->type) }} | {{ e2b($item->number_of_page) }} পাতা</td>
                                                </tr>
                                                <tr>
                                                    <th class="px-4 px-xl-5">{{ __('web.First publication') }} </th>
                                                    <td>{{ $item->published_at }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="px-4 px-xl-5">{{ __('web.Publication') }}</th>
                                                    <td><a href="{{ url('/publisher', $item->publication->slug) }}">{{ $item->publication->{'name'.$locale} }}</a></td>
                                                </tr>
                                                <tr>
                                                    <th class="px-4 px-xl-5">ISBN:</th>
                                                    <td>{{ $item->isbn }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="px-4 px-xl-5">{{ __('web.Language') }}</th>
                                                    <td>{{ $item->language }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="woocommerce-Tabs-panel panel col-xl-8 offset-xl-2 entry-content wc-tab tab-pane fade pt-0" id="review-pill" role="tabpanel" aria-labelledby="review-tab">

                                    <h4 class="font-size-3">{{ __('web.Customer Reviews') }}</h4>
                                    <div class="row mb-8">
                                        <div class="col-md-6 mb-6 mb-md-0">
                                            <div class="d-flex  align-items-center mb-4">
                                                @if(@$item->rating_review->rating)
                                                <span class="font-size-15 font-weight-bold">{{ $item->rating_review->rating }}</span>
                                                <div class="h6 mb-0">
                                                    <span class="font-weight-normal ml-3">{{ $item->rating_review->rating_total }} reviews</span>
                                                    {!! htmlStar($item->rating_review->rating,'font-size-2') !!}
                                                </div>
                                                @else
                                                <span class="font-size-15 font-weight-bold">{{ __('web.No Rating Yet!') }}</span>
                                                @endif
                                            </div>
                                             @if(@$item->rating_review->rating)
                                            <div class="d-md-flex">
                                                <button type="button" class="btn btn-outline-dark rounded-0 px-5 mb-3 mb-md-0">{{ __('web.See all reviews') }}</button>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">

                                            <ul class="list-unstyled pl-xl-4">
                                                @foreach(@$item->rating_review->rating_list as $rv_key => $rv)
                                                <li class="py-2">
                                                    <a class="row align-items-center mx-gutters-2 font-size-2" href="javascript:;">
                                                        <div class="col-auto">
                                                            <span class="text-dark">{{ str_replace("star_", '', $rv_key) }} {{ __('web.stars') }}</span>
                                                        </div>
                                                        <div class="col px-0">
                                                            {!! htmlStar(str_replace("star_", '', $rv_key)) !!}
                                                        </div>
                                                        <div class="col-2">
                                                            <span class="text-secondary">{{ $rv }}</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    </div>
                                    @if($reviews->total()>0)
                                    <h4 class="font-size-3 mb-5">{{ $reviews->firstItem() }}-{{ $reviews->lastItem() }} {{ __('web.of') }} {{ $reviews->total() }} {{ __('web.reviews') }}</h4>
                                    <div class="border-bottom mb-8"></div>
                                    @endif
                                    @foreach($reviews as $rev)
                                    <ul class="list-unstyled mb-8">
                                        <li class="mb-4 pb-5 border-bottom">
                                            <div class="mb-2">
                                                <h6 class="mb-2">{{ __('web.Review by') }} '{{ $rev->name }}'</h6>
                                                <div class="d-flex">
                                                    {!! htmlStar($rev->star,'','mr-4') !!}
                                                    @if($rev->verified)
                                                    <span class="vp">Verified Purchase</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <blockquote class="mb-4 text-lh-md">{{ $rev->message }}</blockquote>
                                            <div class="text-gray-600 mb-4">{{ $rev->updated_at->format('F d, Y') }}</div>
                                        </li>
                                    </ul>
                                    @endforeach
                                    @if($user)
                                    <h4 class="font-size-3 mb-4">{{ __('web.Write a Review') }}</h4>
                                    <form action="" method="POST">
                                        @csrf
                                        @if($review)
                                        <input type="hidden" name="review_id" value="{{ $review->id }}">
                                        @endif
                                        <div class="d-flex align-items-center mb-6">
                                            <h6 class="mb-0">{{ __('web.Select a rating(required)') }}</h6>
                                            <div class="text-gray-darker ml-3 font-size-4">
                                                <div class="rating">
                                                    <label>
                                                        <input type="radio" name="stars" required value="1" />
                                                        <small class="icon fa-star"></small>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" required value="2" />
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" required value="3" />
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>   
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" required value="4" />
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" required value="5" />
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                        <small class="icon fa-star"></small>
                                                    </label>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="js-form-message form-group mb-4">
                                            <label for="review_message" class="form-label text-dark h6 mb-3">{{ __('web.Details please! Your review helps other shoppers.') }}</label>
                                            <textarea class="form-control rounded-0 p-4" rows="7" id="review_message" name="review_message" placeholder="What did you like or dislike? What should other shoppers know before buying?" required data-msg="Please enter your message." data-error-class="u-has-error" data-success-class="u-has-success"></textarea>
                                        </div>
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-dark btn-wide rounded-0 transition-3d-hover">{{ __('web.Submit Review') }}</button>
                                        </div>
                                    </form>
                                    @endif
                                </div>

                                <div class="woocommerce-Tabs-panel panel col-xl-8 offset-xl-2 entry-content wc-tab tab-pane fade pt-0" id="author-pill" role="tabpanel" aria-labelledby="author-tab">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img class="img-fluid" src="{{ ($item->_author->photo) ? showImage($item->_author->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" alt="{{ @$item->_author->{'name'.$locale} }}">
                                        </div>
                                        <div class="col-md-9">
                                            <span class="text-gray-400 font-size-2">{{ __('web.AUTHOR BIOGRAPHY') }}</span>
                                            <h6 class="font-size-7 ont-weight-medium mt-2 mb-3 pb-1">{{ $item->_author->{'name'.$locale} }} ({{ $item->_author->name }})</h6>
                                            <p class="mb-0 text-justify">{{ $item->_author->bio }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <section class="space-bottom-1">
                    <div class="space-1">
                        @include('books.joined_single_line', ['_title'=> __('web.Related Books'), '_link'=>'', '_loop'=> $items, '_show'=> 8, '_bg'=> ''])
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.Book Preview') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="book_preview" oncontextmenu="return false">
                @if($preview)
                @for($i = 1; $i<=$preview[1]; $i++)
                <img src="{{ url('assets/'.$preview[0],$i.'.webp') }}" alt="" width="100%">
                @endfor
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-share" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="icon-container1 d-flex">
                    <div class="smd" onclick="share('twitter');">
                        <i class=" img-thumbnail fass fab fa-twitter fa-2x"
                            style="color:#4c6ef5;background-color: aliceblue"></i>
                        <p>Twitter</p>
                    </div>
                    <div class="smd" onclick="share('facebook');">
                        <i class="img-thumbnail fass fab fa-facebook fa-2x"
                            style="color: #3b5998;background-color: #eceff5;"></i>
                        <p>Facebook</p>
                    </div>
                    <div class="smd" onclick="share('reddit');">
                        <i class="img-thumbnail fass fab fa-reddit-alien fa-2x"
                            style="color: #FF5700;background-color: #fdd9ce;"></i>
                        <p>Reddit</p>
                    </div>
                    <div class="smd" onclick="share('whatsapp');">
                        <i class="img-thumbnail fass fab fa-whatsapp fa-2x"
                            style="color:  #25D366;background-color: #cef5dc;"></i>
                        <p>Whatsapp</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    function share(provider){
        var url = '';
        var share_link = "{{ url()->current() }}";
        if(provider=='facebook'){
            url = 'https://www.facebook.com/sharer/sharer.php?display=popup&u=' + encodeURIComponent(share_link);
        }
        if(provider=='twitter'){
            url = "https://twitter.com/intent/tweet?text=Check out '{{ $item->title }}` by '{{ $item->author }}' in Boiferry&url=" + encodeURIComponent(share_link) +'&hashtags=BoiFerry';
        }
        if(provider=='whatsapp'){
             url = "https://api.whatsapp.com/send?phone=&text=Check out '{{ $item->title }}` by '{{ $item->author }}' in Boiferry. \n\n" + encodeURIComponent(share_link);
        }
        if(provider=='reddit'){
             url = "https://reddit.com/submit?title=Check out '{{ $item->title }}` by '{{ $item->author }}' in Boiferry.&url=" + encodeURIComponent(share_link);
        }
        if(url){
            options = 'toolbar=0,status=0,resizable=1,width=626,height=436';
            window.open(url,'sharer',options);
        }
    }
    @if($item->preview)
    function showPreview(){
        $("#modal-preview").modal({backdrop: 'static', keyboard: false, 'show': true});
    }
    @endif
    fbq('track', 'ViewContent', {
        content_name: "{{ $item->name_bn }}",
        content_category: 'media > books',
        content_ids: ['bf_{{ $item->id }}'],
        content_type: 'product',
        value: {{ $item->sale }},
        currency: 'BDT'
    });

</script>
@endpush