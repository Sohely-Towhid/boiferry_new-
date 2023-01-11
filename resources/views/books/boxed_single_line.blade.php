@if($_title)
<div class="container">
    <header class="d-md-flex justify-content-between align-items-center mb-5">
        <h1 class="font-size-7 mb-4 mb-md-0">{{ $_title }}</h1>
        @if($_link)
        <a href="{{ url(@$_link) }}" class="d-flex h-primary">{{ __('web.See More') }}</a>
        @endif
    </header>
</div>
@endif
@php $locale = (App::currentLocale()=='en') ? '' : '_bn'; @endphp
<div class="container">
<ul class="js-slick-carousel products list-unstyled u-slick--gutters-3 my-0 ribbon t7" data-pagi-classes="d-xl-none text-center u-slick__pagination u-slick__pagination mt-7" data-arrows-classes="d-none d-xl-block u-slick__arrow u-slick__arrow--v1 u-slick__arrow-centered--y rounded-circle box-shadow-1" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-inner u-slick__arrow-inner--left ml-lg-n10" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-inner u-slick__arrow-inner--right mr-lg-n10" data-slides-show="{{ $_show }}" data-infinite="true" data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 4}},{"breakpoint": 1199,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 3}}, {"breakpoint": 768,"settings": {"slidesToShow": 3}}, {"breakpoint": 554,"settings": {"slidesToShow": 3, "arrows": true, "dots": false}}]'>
    @if($_loop)
        @foreach($_loop as $_item)
        <li class="product product__space border rounded-md bg-white position-relative">
            @php 
                $ppp = _percent($_item->rate, $_item->sale,"% ".__('web.off'));
                $_item->images = (is_array($_item->images)) ? $_item->images : json_decode($_item->images, true); 
            @endphp
            @if($ppp)
            <span class="ribbon1"><div class="discount">{{ $ppp }}</div></span>
            @endif
            <div class="products">
                <div class="product__inner overflow-hidden p-3">
                    <div class="woocommerce-LoopProduct-link woocommerce-loop-product__link d-block position-relative">
                        <div class="woocommerce-loop-product__thumbnail">
                            <a href="{{ url('/book/'.$_item->slug) }}" class="d-block"><img data-src="{{ showImage(@$_item->images[0], 'sm') }}" class="cover d-block mx-auto attachment-shop_catalog size-shop_catalog wp-post-image img-fluid" alt="image-description"></a>
                        </div>
                        <div class="woocommerce-loop-product__body product__body pt-3 bg-white">
                            <div class="text-uppercase font-size-1 mb-1 text-truncate"><a href="#">{{ __('web.'.$_item->type) }}</a></div>
                            <div class="woocommerce-loop-product__title product__title h6 text-lh-md mb-1 text-height-2 crop-text-2 h-dark">
                                <a href="{{ url('/book/'.$_item->slug) }}">{{ $_item->{'title'.$locale} }}</a>
                            </div>
                            <div class="font-size-2  mb-1 text-truncate"><a href="{{ url('author/'.$_item->author_id) }}" class="text-gray-700">{{ $_item->{'author'.$locale} }}</a></div>
                            <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳</span>{{ e2b($_item->sale) }}</span>
                            </div>
                        </div>
                        <div class="product__hover d-flex align-items-center">
                            @if($_item->stock>0)
                            <a href="javascript:{};" onclick="add2cart({{ $_item->id }}, 1, false, $(this));" class="text-uppercase text-dark h-dark font-weight-medium mr-auto">
                                {{-- <span class="product__add-to-cart">{{ __('web.ADD TO CART') }}</span> --}}
                                <span class="product__add-to-cart-icon font-size-4 text-danger"><i class="fal fa-shopping-bag"></i></span>
                            </a>
                            @else
                            <a href="javascript:{};" class="text-uppercase text-dark h-dark font-weight-medium mr-auto">
                                <span class="product__add-to-cart">{{ __('web.SOLDOUT') }}</span>
                                <span class="product__add-to-cart-icon font-size-4 text-danger"><i class="fal fa-empty-set"></i></span>
                            </a>
                            @endif
                            <a href="javascript:{};" onclick="add2wishlist({{ $_item->id }});" class="h-p-bg btn btn-outline-dark border-0">
                                <i class="fas fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
    @else
        <div class="text-center">No Book Found</div>
    @endif
    
</ul>
</div>