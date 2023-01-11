{{-- https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/html-demo/home/index.html --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Winners Bazar (Book Zone)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/vendor/font-awesome/css/fontawesome-all.min.css"> -->
    <!-- <link rel="stylesheet" href="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/vendor/flaticon/font/flaticon.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/web/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/web/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/mod.css') }}">
    <!-- Facebook Pixel Code -->
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '547440866499152');fbq('track', 'PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=547440866499152&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>

    <header id="site-header" class="site-header__v2 site-header__white-text">
        <div class="masthead">
            <div class="bg-secondary-gray-800" id="top_search">
                <div class="container pt-3 pt-md-4 pb-3 pb-md-5">
                    <div class="d-flex align-items-center position-relative flex-wrap">
                        <div class="offcanvas-toggler mr-4">
                            <a id="sidebarNavToggler2" href="javascript:;" role="button" class="cat-menu" aria-controls="sidebarContent2" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent2" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 100 }' data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft" data-unfold-duration="100">
                                <svg width="20px" height="18px">
                                    <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,-0.000 L20.000,-0.000 L20.000,2.000 L-0.000,2.000 L-0.000,-0.000 Z" />
                                    <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,8.000 L15.000,8.000 L15.000,10.000 L-0.000,10.000 L-0.000,8.000 Z" />
                                    <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,16.000 L20.000,16.000 L20.000,18.000 L-0.000,18.000 L-0.000,16.000 Z" />
                                </svg>
                            </a>
                        </div>
                        <div class="site-branding pr-7">
                            <a href="index.html" class="d-block mb-2">
                                <h1>বইপোকা</h1>
                            </a>
                        </div>
                        <div class="site-search ml-xl-0 ml-md-auto w-r-100 flex-grow-1 mr-md-5 mt-2 mt-md-0 order-1 order-md-0">
                            <form class="form-inline my-2 my-xl-0" action="{{ url('search') }}">
                                <div class="input-group input-group-borderless w-100">
                                    <input type="search" name="q" id="search_text" autocomplete="off" class="form-control border-left rounded-left-1 rounded-left-xl-0 px-3" placeholder="Search for books by keyword" aria-label="Amount (to the nearest dollar)">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary-green px-3 py-2" type="submit"><i class="mx-1 fas fa-search text-white"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex align-items-center">
                            <a id="sidebarNavToggler" href="javascript:;" role="button" aria-controls="sidebarContent" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 500}' data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                                <div class="d-flex align-items-center text-white font-size-2 text-lh-sm">
                                    <i class="fal fa-user font-size-6"></i>
                                    <div class="ml-2 d-none d-lg-block">
                                        <span class="text-secondary-gray-1080 font-size-1">সাইন ইন</span>
                                        <div class="">আমার একাউন্ট</div>
                                    </div>
                                </div>
                            </a>


                            <a id="sidebarNavToggler1" href="javascript:;" role="button" class="ml-4 d-none d-lg-block" aria-controls="sidebarContent1" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent1" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 500}' data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                                <div class="d-flex align-items-center text-white font-size-2 text-lh-sm position-relative">
                                    <span class="position-absolute bg-white width-16 height-16 rounded-circle d-flex align-items-center justify-content-center text-dark font-size-n9 left-0 top-0 ml-n2 mt-n1">3</span>
                                    <i class="fal fa-shopping-bag font-size-6"></i>
                                    <div class="ml-2">
                                        <span class="text-secondary-gray-1080 font-size-1">My Cart</span>
                                        <div class="">$40.93</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-secondary-black-200 d-none d-md-block">
                <div class="container">
                    <div class="d-flex align-items-center justify-content-center position-relative">
                        <div class="site-navigation mr-auto d-none d-xl-block">
                            @include('books.menu')
                        </div>
                        <div class="secondary-navigation">
                            <ul class="nav">
                                <li class="nav-item"><a href="#" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">অতিরিক্ত ছাড়ের বই</a></li>
                                <li class="nav-item"><a href="#" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">আলোচিত বই</a></li>
                                <li class="nav-item"><a href="#" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">গিফট কার্ড</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="space-bottom-3">
        <div class="bg-gray-200 space-2 space-lg-0 bg-img-hero" style="background-image: url(https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/1920x588/img1.jpg);">
            <div class="container">
                <div class="js-slick-carousel u-slick" data-pagi-classes="text-center u-slick__pagination position-absolute right-0 left-0 mb-n8 mb-lg-4 bottom-0">
                    <div class="js-slide">
                        <div class="hero row min-height-588 align-items-center">
                            <div class="col-lg-7 col-wd-6 mb-4 mb-lg-0">
                                <div class="media-body mr-wd-4 align-self-center mb-4 mb-md-0">
                                    <p class="hero__pretitle text-uppercase font-weight-bold text-gray-400 mb-2" data-scs-animation-in="fadeInUp" data-scs-animation-delay="200">The Bookworm Editors'</p>
                                    <h2 class="hero__title font-size-14 mb-4" data-scs-animation-in="fadeInUp" data-scs-animation-delay="300">
                                        <span class="hero__title-line-1 font-weight-regular d-block">Featured Books of the</span>
                                        <span class="hero__title-line-2 font-weight-bold d-block">February</span>
                                    </h2>
                                    <a href="../shop/v1.html" class="btn btn-dark btn-wide rounded-0 hero__btn" data-scs-animation-in="fadeInLeft" data-scs-animation-delay="400">See More</a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-wd-6" data-scs-animation-in="fadeInRight" data-scs-animation-delay="500">
                                <img class="img-fluid" src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/800x420/img1.png" alt="image-description">
                            </div>
                        </div>
                    </div>
                    <div class="js-slide">
                        <div class="hero row min-height-588 align-items-center">
                            <div class="col-lg-7 col-wd-6 mb-4 mb-lg-0">
                                <div class="media-body mr-wd-4 align-self-center mb-4 mb-md-0">
                                    <p class="hero__pretitle text-uppercase font-weight-bold text-gray-400 mb-2" data-scs-animation-in="fadeInUp" data-scs-animation-delay="200">The Bookworm Editors'</p>
                                    <h2 class="hero__title font-size-14 mb-4" data-scs-animation-in="fadeInUp" data-scs-animation-delay="300">
                                        <span class="hero__title-line-1 font-weight-regular d-block">Featured Books of the</span>
                                        <span class="hero__title-line-2 font-weight-bold d-block">February</span>
                                    </h2>
                                    <a href="../shop/v1.html" class="btn btn-dark btn-wide rounded-0 hero__btn" data-scs-animation-in="fadeInLeft" data-scs-animation-delay="400">See More</a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-wd-6" data-scs-animation-in="fadeInRight" data-scs-animation-delay="500">
                                <img class="img-fluid" src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/800x420/img1.png" alt="image-description">
                            </div>
                        </div>
                    </div>
                    <div class="js-slide">
                        <div class="hero row min-height-588 align-items-center">
                            <div class="col-lg-7 col-wd-6 mb-4 mb-lg-0">
                                <div class="media-body mr-wd-4 align-self-center mb-4 mb-md-0">
                                    <p class="hero__pretitle text-uppercase font-weight-bold text-gray-400 mb-2" data-scs-animation-in="fadeInUp" data-scs-animation-delay="200">The Bookworm Editors'</p>
                                    <h2 class="hero__title font-size-14 mb-4" data-scs-animation-in="fadeInUp" data-scs-animation-delay="300">
                                        <span class="hero__title-line-1 font-weight-regular d-block">Featured Books of the</span>
                                        <span class="hero__title-line-2 font-weight-bold d-block">February</span>
                                    </h2>
                                    <a href="../shop/v1.html" class="btn btn-dark btn-wide rounded-0 hero__btn" data-scs-animation-in="fadeInLeft" data-scs-animation-delay="400">See More</a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-wd-6" data-scs-animation-in="fadeInRight" data-scs-animation-delay="500">
                                <img class="img-fluid" src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/800x420/img1.png" alt="image-description">
                            </div>
                        </div>
                    </div>
                    <div class="js-slide">
                        <div class="hero row min-height-588 align-items-center">
                            <div class="col-lg-7 col-wd-6 mb-4 mb-lg-0">
                                <div class="media-body mr-wd-4 align-self-center mb-4 mb-md-0">
                                    <p class="hero__pretitle text-uppercase font-weight-bold text-gray-400 mb-2" data-scs-animation-in="fadeInUp" data-scs-animation-delay="200">The Bookworm Editors'</p>
                                    <h2 class="hero__title font-size-14 mb-4" data-scs-animation-in="fadeInUp" data-scs-animation-delay="300">
                                        <span class="hero__title-line-1 font-weight-regular d-block">Featured Books of the</span>
                                        <span class="hero__title-line-2 font-weight-bold d-block">February</span>
                                    </h2>
                                    <a href="../shop/v1.html" class="btn btn-dark btn-wide rounded-0 hero__btn" data-scs-animation-in="fadeInLeft" data-scs-animation-delay="400">See More</a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-wd-6" data-scs-animation-in="fadeInRight" data-scs-animation-delay="500">
                                <img class="img-fluid" src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/800x420/img1.png" alt="image-description">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="space-bottom-3">
        <div class="container">
            <header class="mb-5 d-md-flex justify-content-between align-items-center">
                <h2 class="font-size-7 mb-3 mb-md-0">সর্বশেষ বিক্রিত বইসমূহ</h2>
                <a href="../shop/v1.html" class="h-primary d-block">View All <i class="glyph-icon flaticon-next"></i></a>
            </header>
            <div class="js-slick-carousel products no-gutters border-top border-left border-right" data-pagi-classes="d-xl-none text-center position-absolute right-0 left-0 u-slick__pagination mt-4 mb-0" data-arrows-classes="d-none d-xl-block u-slick__arrow u-slick__arrow-centered--y" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-inner u-slick__arrow-inner--left ml-lg-n10" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-inner u-slick__arrow-inner--right mr-lg-n10" data-slides-show="8" data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 4}},{"breakpoint": 1199,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}}, {"breakpoint": 768,"settings": {"slidesToShow": 1}}, {"breakpoint": 554,"settings": {"slidesToShow": 1}}]'>
                @for($i=1; $i<12; $i++)
                <div class="product">
                    <div class="product__inner overflow-hidden p-3 p-md-4d875">
                        <div class="woocommerce-LoopProduct-link woocommerce-loop-product__link d-block position-relative">
                            <div class="woocommerce-loop-product__thumbnail">
                                <a href="../shop/single-product-v1.html" class="d-block"><img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/150x226/img5.jpg" class="img-fluid d-block mx-auto attachment-shop_catalog size-shop_catalog wp-post-image img-fluid" alt="image-description"></a>
                            </div>
                            <div class="woocommerce-loop-product__body product__body pt-3 bg-white">
                                <div class="text-uppercase font-size-1 mb-1 text-truncate"><a href="../shop/single-product-v1.html">Paperback</a></div>
                                <h2 class="woocommerce-loop-product__title product__title h6 text-lh-md mb-1 text-height-2 crop-text-2 h-dark"><a href="../shop/single-product-v1.html">Think Like a Monk: Train Your Mind for Peace and Purpose Everyday</a></h2>
                                <div class="font-size-2  mb-1 text-truncate"><a href="../others/authors-single.html" class="text-gray-700">Jay Shetty</a></div>
                                <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                    <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>29</span>
                                </div>
                            </div>
                            <div class="product__hover d-flex align-items-center">
                                <a href="../shop/single-product-v1.html" class="text-uppercase text-dark h-dark font-weight-medium mr-auto">
                                    <span class="product__add-to-cart">ADD TO CART</span>
                                    <span class="product__add-to-cart-icon font-size-4"><i class="flaticon-icon-126515"></i></span>
                                </a>
                                <a href="../shop/single-product-v1.html" class="mr-1 h-p-bg btn btn-outline-primary border-0">
                                    <i class="flaticon-switch"></i>
                                </a>
                                <a href="../shop/single-product-v1.html" class="h-p-bg btn btn-outline-primary border-0">
                                    <i class="flaticon-heart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor

            </div>
        </div>
    </section>



    <section class="space-bottom-3">
        <div class="space-3 bg-gray-200">
            <div class="container">
                <header class="d-md-flex justify-content-between align-items-center mb-5">
                    <h2 class="font-size-7 mb-0">২০২১ এর বেস্টসেলার বইসমূহ</h2>
                    <a href="../shop/v3.html" class="d-flex align-items-center text-dark">View All<span class="flaticon-next font-size-3 ml-2"></span></a>
                </header>
                <ul class="js-slick-carousel products list-unstyled u-slick--gutters-3 my-0" data-pagi-classes="d-xl-none text-center u-slick__pagination u-slick__pagination mt-7" data-arrows-classes="d-none d-xl-block u-slick__arrow u-slick__arrow--v1 u-slick__arrow-centered--y rounded-circle box-shadow-1" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-inner u-slick__arrow-inner--left ml-lg-n10" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-inner u-slick__arrow-inner--right mr-lg-n10" data-slides-show="5" data-responsive='[{"breakpoint": 1500,"settings": {"slidesToShow": 4}}, {"breakpoint": 1199,"settings": {"slidesToShow": 3}}, {"breakpoint": 992,"settings": {"slidesToShow": 2}}]'>
                    @for($i=1; $i<12; $i++)
                    <li class="product product__space border rounded-md bg-white">
                        <div class="product__inner overflow-hidden p-3 p-md-4d875">
                            <div class="woocommerce-LoopProduct-link woocommerce-loop-product__link d-block position-relative">
                                <div class="woocommerce-loop-product__thumbnail">
                                    <a href="../shop/single-product-v3.html" class="d-block"><img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/150x226/img1.jpg" class="d-block mx-auto attachment-shop_catalog size-shop_catalog wp-post-image img-fluid" alt="image-description"></a>
                                </div>
                                <div class="woocommerce-loop-product__body product__body pt-3 bg-white">
                                    <div class="text-uppercase font-size-1 mb-1 text-truncate"><a href="../shop/single-product-v3.html">Paperback</a></div>
                                    <h2 class="woocommerce-loop-product__title product__title h6 text-lh-md mb-1 text-height-2 crop-text-2 h-dark"><a href="../shop/single-product-v3.html">Winter Garden</a></h2>
                                    <div class="font-size-2  mb-1 text-truncate"><a href="../others/authors-single.html" class="text-gray-700">Jay Shetty</a></div>
                                    <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>29</span>
                                    </div>
                                    <div class="product__rating d-none align-items-center font-size-2">
                                        <div class="text-yellow-darker mr-2">
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        </div>
                                        <div class="">(3,714)</div>
                                    </div>
                                </div>
                                <div class="product__hover d-flex align-items-center">
                                    <a href="../shop/single-product-v3.html" class="text-uppercase text-dark h-dark font-weight-medium mr-auto">
                                        <span class="product__add-to-cart">ADD TO CART</span>
                                        <span class="product__add-to-cart-icon font-size-4"><i class="flaticon-icon-126515"></i></span>
                                    </a>
                                    <a href="../shop/single-product-v3.html" class="mr-1 h-p-bg btn btn-outline-dark border-0">
                                        <i class="flaticon-switch"></i>
                                    </a>
                                    <a href="../shop/single-product-v3.html" class="h-p-bg btn btn-outline-dark border-0">
                                        <i class="flaticon-heart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endfor
                </ul>
            </div>
        </div>
    </section>


    <section class="space-bottom-3">
        <div class="container">
            <header class="d-md-flex justify-content-between align-items-center mb-8">
                <h2 class="font-size-7 mb-3 mb-md-0">এ সপ্তাহের বেস্ট সেলার লেখকগন</h2>
                <a href="../others/authors-list.html" class="h-primary d-block">View All <i class="glyph-icon flaticon-next"></i></a>
            </header>
            <ul class="row rows-cols-5 no-gutters authors list-unstyled js-slick-carousel u-slick" data-slides-show="8" data-arrows-classes="u-slick__arrow u-slick__arrow-centered--y" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-inner u-slick__arrow-inner--left ml-lg-n10" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-inner u-slick__arrow-inner--right mr-lg-n10" data-responsive='[{"breakpoint": 1025,"settings": {"slidesToShow": 3}}, {"breakpoint": 992,"settings": {"slidesToShow": 2}}, {"breakpoint": 768,"settings": {"slidesToShow": 1}}, {"breakpoint": 554,"settings": {"slidesToShow": 1}}]'>
                @for($i=1; $i<12; $i++)
                <li class="author col">
                    <a href="../others/authors-single.html" class="text-reset">
                        <img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/140x140/img1.jpg" class="mx-auto mb-5 d-block rounded-circle" alt="image-description">
                        <div class="author__body text-center">
                            <h2 class="author__name h6 mb-0">Barbara O'Neil</h2>
                            <div class="text-gray-700 font-size-2">25 Published Books</div>
                        </div>
                    </a>
                </li>
                @endfor
            </ul>
        </div>
    </section>


    <footer class="site-footer_v2">
        <div class="space-top-3 bg-gray-850">
            <div class="pb-5 space-bottom-lg-3">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 mb-6 mb-lg-0">
                            <div class="text-gray-450 mb-4">Winners Bazar is the Newest Online Shopping Mall in Bangladesh. We brings the most amazing products at the best value – delivered right at your door!</div>
                           
                            <address class="font-size-2 mb-5">
                                <span class="mb-2 font-weight-normal text-gray-450">
                                    Address: 1418 River Drive, Suite 35 Cottonhall, CA 9622 <br> United States
                                </span>
                            </address>
                            <div class="mb-4 h-white">
                                <a href="mailto:sale@bookworm.com" class="font-size-2 d-block text-gray-450 mb-1">sale@bookworm.com</a>
                                <a href="tel:+1246-345-0695" class="font-size-2 d-block text-gray-450">+1 246-345-0695</a>
                            </div>
                            <ul class="list-unstyled mb-0 d-flex">
                                    <li class="h-white btn pl-0">
                                        <a class="text-gray-450" href="#">
                                            <span class="fab fa-instagram"></span>
                                        </a>
                                    </li>
                                    <li class="h-white btn">
                                        <a class="text-gray-450" href="#">
                                            <span class="fab fa-facebook-f"></span>
                                        </a>
                                    </li>
                                    <li class="h-white btn">
                                        <a class="text-gray-450" href="#">
                                            <span class="fab fa-youtube"></span>
                                        </a>
                                    </li>
                                    <li class="h-white btn">
                                        <a class="text-gray-450" href="#">
                                            <span class="fab fa-twitter"></span>
                                        </a>
                                    </li>
                                    <li class="h-white btn">
                                        <a class="text-gray-450" href="#">
                                            <span class="fab fa-pinterest"></span>
                                        </a>
                                    </li>
                                </ul>
                        </div>

                        <div class="col-lg-2 mb-6 mb-lg-0">
                            <h4 class="font-size-3 font-weight-medium mb-2 mb-xl-5 pb-xl-1 text-white">Customer Service</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="h-white pb-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Help Center</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Returns</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Product Recalls</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Accessibility</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Contact Us</a>
                                </li>
                                <li class="h-white pt-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Store Pickup</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 mb-6 mb-lg-0">
                            <h4 class="font-size-3 font-weight-medium mb-2 mb-xl-5 pb-xl-1 text-white">Policy</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="h-white pb-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Return Policy</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Terms Of Use</a>
                                </li>
                                <li class="h-white py-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Security</a>
                                </li>
                                <li class="h-white pt-2">
                                    <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="#">Privacy</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-1 border-top border-gray-750">
                <div class="container">
                    <div class="d-lg-flex text-center text-lg-left justify-content-between align-items-center">
                        <p class="mb-4 mb-lg-0 font-size-2 text-gray-450">©{{ date('Y') }} Winners Bazar. All rights reserved</p>
                        <div class="ml-auto d-lg-flex justify-content-xl-end align-items-center">
                            <p class="mb-4 mb-lg-0 font-size-2 text-gray-450">Developed by Bluedot Technology Ltd</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>       


    <aside id="sidebarContent1" class="u-sidebar u-sidebar__xl" aria-labelledby="sidebarNavToggler1">
        <div class="u-sidebar__scroller js-scrollbar">
            <div class="u-sidebar__container">
                <div class="u-header-sidebar__footer-offset">

                    <div class="d-flex align-items-center position-absolute top-0 right-0 z-index-2 mt-5 mr-md-6 mr-4">
                        <button type="button" class="close ml-auto" aria-controls="sidebarContent1" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent1" data-unfold-type="css-animation" data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                            <span aria-hidden="true">Close <i class="fas fa-times ml-2"></i></span>
                        </button>
                    </div>


                    <div class="u-sidebar__body">
                        <div class="u-sidebar__content u-header-sidebar__content">

                            <header class="border-bottom px-4 px-md-6 py-4">
                                <h2 class="font-size-3 mb-0 d-flex align-items-center"><i class="flaticon-icon-126515 mr-3 font-size-5"></i>Your shopping bag (3)</h2>
                            </header>

                            <div class="px-4 py-5 px-md-6 border-bottom">
                                <div class="media">
                                    <a href="#" class="d-block"><img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/120x180/img6.jpg" class="img-fluid" alt="image-description"></a>
                                    <div class="media-body ml-4d875">
                                        <div class="text-primary text-uppercase font-size-1 mb-1 text-truncate"><a href="#">Hard Cover</a></div>
                                        <h2 class="woocommerce-loop-product__title h6 text-lh-md mb-1 text-height-2 crop-text-2">
                                            <a href="#" class="text-dark">The Ride of a Lifetime: Lessons Learned from 15 Years as CEO</a>
                                        </h2>
                                        <div class="font-size-2 mb-1 text-truncate"><a href="#" class="text-gray-700">Robert Iger</a></div>
                                        <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                            <span class="woocommerce-Price-amount amount">1 x <span class="woocommerce-Price-currencySymbol">$</span>125.30</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 ml-3">
                                        <a href="#" class="text-dark"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-5 px-md-6 border-bottom">
                                <div class="media">
                                    <a href="#" class="d-block"><img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/120x180/img6.jpg" class="img-fluid" alt="image-description"></a>
                                    <div class="media-body ml-4d875">
                                        <div class="text-primary text-uppercase font-size-1 mb-1 text-truncate"><a href="#">Hard Cover</a></div>
                                        <h2 class="woocommerce-loop-product__title h6 text-lh-md mb-1 text-height-2 crop-text-2">
                                            <a href="#" class="text-dark">The Rural Diaries: Love, Livestock, and Big Life Lessons Down</a>
                                        </h2>
                                        <div class="font-size-2 mb-1 text-truncate"><a href="#" class="text-gray-700">Hillary Burton</a></div>
                                        <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                            <span class="woocommerce-Price-amount amount">2 x <span class="woocommerce-Price-currencySymbol">$</span>200</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 ml-3">
                                        <a href="#" class="text-dark"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-5 px-md-6 border-bottom">
                                <div class="media">
                                    <a href="#" class="d-block"><img src="https://demo2.madrasthemes.com/bookworm-html/redesigned-octo-fiesta/assets/img/120x180/img6.jpg" class="img-fluid" alt="image-description"></a>
                                    <div class="media-body ml-4d875">
                                        <div class="text-primary text-uppercase font-size-1 mb-1 text-truncate"><a href="#">Paperback</a></div>
                                        <h2 class="woocommerce-loop-product__title h6 text-lh-md mb-1 text-height-2 crop-text-2">
                                            <a href="#" class="text-dark">Russians Among Us: Sleeper Cells, Ghost Stories, and the Hunt.</a>
                                        </h2>
                                        <div class="font-size-2 mb-1 text-truncate"><a href="#" class="text-gray-700">Gordon Corera</a></div>
                                        <div class="price d-flex align-items-center font-weight-medium font-size-3">
                                            <span class="woocommerce-Price-amount amount">6 x <span class="woocommerce-Price-currencySymbol">$</span>100</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 ml-3">
                                        <a href="#" class="text-dark"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-5 px-md-6 d-flex justify-content-between align-items-center font-size-3">
                                <h4 class="mb-0 font-size-3">Subtotal:</h4>
                                <div class="font-weight-medium">$750.00</div>
                            </div>
                            <div class="px-4 mb-8 px-md-6">
                                <a href="../shop/cart.html" class="btn btn-block py-4 rounded-0 btn-outline-dark mb-4">View Cart</a>
                                <a href="../shop/checkout.html" class="btn btn-block py-4 rounded-0 btn-dark">Checkout</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </aside>



    <aside id="sidebarContent2" class="u-sidebar u-sidebar__md u-sidebar--left" aria-labelledby="sidebarNavToggler2">
        <div class="u-sidebar__scroller js-scrollbar">
            <div class="u-sidebar__container">
                <div class="u-header-sidebar__footer-offset">

                    <div class="u-sidebar__body">
                        <div class="u-sidebar__content u-header-sidebar__content">

                            <header class="border-bottom px-4 px-md-5 py-4 d-flex align-items-center justify-content-between">
                                <h2 class="font-size-3 mb-0">SHOP BY CATEGORY</h2>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="close ml-auto" aria-controls="sidebarContent2" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent2" data-unfold-type="css-animation" data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft" data-unfold-duration="500">
                                        <span aria-hidden="true"><i class="fas fa-times ml-2"></i></span>
                                    </button>
                                </div>

                            </header>

                            <div class="border-bottom">
                                <div class="zeynep pt-4">
                                    <ul>
                                        <li>
                                            <a href="">Home</a>
                                        </li>
                                        <li class="has-submenu">
                                            <a href="#" data-submenu="art-photo">Arts & Photography</a>
                                            <div id="art-photo" class="submenu">
                                                <div class="submenu-header" data-submenu-close="art-photo">
                                                    <a href="#">Arts & Photography</a>
                                                </div>
                                                <ul>
                                                    <li>
                                                        <a href="#">Architecture</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Business of Art</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Collections, Catalogs & Exhibitions</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Decorative Arts & Design</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Drawing</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Fashion</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Graphic Design</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>


    <script src="{{ asset('assets/web/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/jquery.zeynep.js') }}"></script>
    <script src="{{ asset('assets/web/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap-autocomplete.min.js') }}"></script>

    <script src="{{ asset('assets/web/js/hs.core.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.unfold.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.malihu-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.header.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.slick-carousel.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.selectpicker.js') }}"></script>
    <script src="{{ asset('assets/web/js/hs.show-animation.js') }}"></script>

    <script>
          $(document).on('ready', function () {
            // initialization of unfold component
            $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

            // initialization of slick carousel
            $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

            // initialization of header
            $.HSCore.components.HSHeader.init($('#header'));

            // initialization of malihu scrollbar
            $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

            // initialization of show animations
            $.HSCore.components.HSShowAnimation.init('.js-animation-link');

            // init zeynepjs
            var zeynep = $('.zeynep').zeynep({
                onClosed: function () {
                    // enable main wrapper element clicks on any its children element
                    $("body main").attr("style", "");

                    console.log('the side menu is closed.');
                },
                onOpened: function () {
                    // disable main wrapper element clicks on any its children element
                    $("body main").attr("style", "pointer-events: none;");

                    console.log('the side menu is opened.');
                }
            });

            // handle zeynep overlay click
            $(".zeynep-overlay").click(function () {
                zeynep.close();
            });

            // Fixed Top Search
            // $("#top_search").addClass('fixed-top');

            $(window).scroll(function(){
                if (window.scrollY > 300) {
                    $("#top_search").addClass('animated fadeInDown fixed-top');
                }else{
                    $("#top_search").removeClass('animated fadeInDown fixed-top');
                }
            });

            $('#search_text').autoComplete({
                minLength: 2,
                resolverSettings: {
                    url: '{{ url('ajax-search') }}',
                    requestThrottling: 250
                },
                formatResult: function (item) {
                    return {
                        value: item.id,
                        text: item.title,
                        html: [ 
                            '<div class="row justify-content-between" onclick="showProduct('+ item.id +');">' +
                            '<img width="40px" src="https://s3-ap-southeast-1.amazonaws.com/rokomari110/ProductNew20190903/45X64/208f6abd84b4_4967.jpg" alt="">' +
                            '<div style="width: calc(100% - 140px); padding-left: 10px;"><strong>' + item.title +'</strong><br>' + item.author + '</div>' +
                            '<div style="width: 100px;"><span class="badge text-black badge-'+ item.stock_color +'">' + item.stock +'</span><br>' + item.sale +' টাকা</div>' +
                            ' </div>'
                        ] 
                    };
                },
            });

            // open side menu if the button is clicked
            $(".cat-menu").click(function () {
                if ($("html").hasClass("zeynep-opened")) {
                    zeynep.close();
                } else {
                    zeynep.open();
                }
            });
        });

          function showProduct(id){
            alert(id);
          }
    </script>


</body>
</html>
