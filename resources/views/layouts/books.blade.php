@php
$setting = loadSetting('book', true);
$user = Auth::user();
$locale = (App::currentLocale()=='en') ? 'English' : 'বাংলা';
$free_shipping = @$setting['book_home_free_shipping']->value;
$popup = App\Models\Popup::where('status',1)->orderBy('id','desc')->first();
$top_ad = @$setting['book_home_top_ad'];
$top_ad = ($top_ad && @$top_ad->value->status==1) ? $top_ad : false;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ __('web.Boiferry') }} - @yield('title',__('home'))</title>
    <meta charset="utf-8">
    @stack('seo')
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#449f3f">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <style>#loader{min-height: 100vh;background: #f5f9ff;display: flex;justify-content: center;align-items: center;z-index: 999999999;position: fixed;right: 0;left: 0;top: 0;bottom: 0;}@keyframes wiggle {0% { transform: rotate(0deg); }80% { transform: rotate(0deg); }85% { transform: rotate(5deg); }95% { transform: rotate(-5deg); }100% { transform: rotate(0deg); }}.wiggle {display: inline-block;animation: wiggle .5s infinite;}</style> --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://boiferry.com/">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('assets/web/css/web.css')) }}">
    <style>#main-slider {min-height: 300px;}#main-slider .js-slide{display: none;}.slick-initialized .js-slide {display: block !important;}@media only screen and (max-width: 600px) {#main-slider {min-height: 105px;} .floating-cart{ top: 50%; display: none; } .pagination .page-item{display: none;}.pagination .page-item:first-child, .pagination .page-item:last-child, .pagination .page-item.active{display: block;} }</style>
    @if(date('m-d')=='12-16')
    <style>
        .bg-secondary-gray-800{
            background: #006a4e !important;
        }
        .bg-secondary-black-200{
            background: #f42a41 !important;
        }
        .btn-primary-green, .btn-primary-green:hover{
            background-color: #f42a41;
            border-color: #f42a41;
        }
    </style>
    @endif
</head>
<body>
    <div id="fb-root"></div>
    <div id="fb-customer-chat" class="fb-customerchat"></div>

    {{-- <div id="loader">
        <div class="text-center wiggle">
            <img src="{{ asset('assets/images/logos/boiferry-main-color.svg') }}" alt="Boiferry" width="150px"><br>
            <h3>বইয়ের জগতে স্বাগতম</h3>
        </div>
    </div> --}}

    <div class="text-center ajax-loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div id="load_main">
        @if($top_ad)
        <div class="text-center">
            <a href="{{ @$top_ad->value->link }}">
                <img src="{{ showImage($top_ad->value->image) }}" class="img-responsive" alt="">
            </a>
        </div>
        @endif
        <header id="site-header" class="site-header__v2 site-header__white-text">
            <div class="topbar d-none d-md-block bg-secondary-gray-800">
                <div class="container">
                    <div class="topbar__nav d-md-flex justify-content-between align-items-center font-size-2">
                        <ul class="topbar__nav--left nav">
                            @if($free_shipping>0)
                            <li class="nav-item"><span class="text-white"><i class="fa fa-shipping-fast mr-2"></i>{{ __('web.Free Shipping on Orders Over', ['amount'=> e2b($free_shipping)]) }}</span></li>
                            @endif
                        </ul>
                        <ul class="topbar__nav--right nav">
                            
                            <li class="nav-item"><a href="{{ url('my-account/orders') }}" class="nav-link p-2 link-black-100 d-flex align-items-center"><i class="fa fa-route mr-2"></i> {{ __('web.Track Your Order') }}</a></li>
                            
                            {{-- <li class="nav-item">
                                <div class="position-relative h-100">
                                    <a id="basicDropdownHoverInvoker" class="d-flex align-items-center h-100 dropdown-nav-link p-2 dropdown-toggle nav-link link-black-100" href="javascript:;" role="button" aria-controls="basicDropdownHover" aria-haspopup="true" aria-expanded="false" data-unfold-event="hover" data-unfold-target="#basicDropdownHover" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-delay="300" data-unfold-hide-on-scroll="true" data-unfold-animation-in="slideInUp" data-unfold-animation-out="fadeOut">
                                        USD <i class=""></i>
                                    </a>
                                    <div id="basicDropdownHover" class="dropdown-menu dropdown-unfold right-0 left-auto u-unfold--css-animation u-unfold--hidden fadeOut" aria-labelledby="basicDropdownHoverInvoker" style="animation-duration: 300ms; right: 0px;">
                                        <a class="dropdown-item active" href="#">INR</a>
                                        <a class="dropdown-item" href="#">Euro</a>
                                        <a class="dropdown-item" href="#">Yen</a>
                                    </div>
                                </div>
                            </li> --}}
                            <li class="nav-item">
                                <div class="position-relative h-100">
                                    <a id="basicDropdownHoverInvoker1" class="d-flex align-items-center h-100 dropdown-nav-link p-2 dropdown-toggle nav-link link-black-100" href="javascript:;" role="button" aria-controls="basicDropdownHover1" aria-haspopup="true" aria-expanded="false" data-unfold-event="hover" data-unfold-target="#basicDropdownHover1" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-delay="300" data-unfold-hide-on-scroll="true" data-unfold-animation-in="slideInUp" data-unfold-animation-out="fadeOut">
                                        {{ $locale }} <i class=""></i>
                                    </a>
                                    <div id="basicDropdownHover1" class="dropdown-menu dropdown-unfold right-0 left-auto u-unfold--css-animation u-unfold--hidden fadeOut" aria-labelledby="basicDropdownHoverInvoker1" style="animation-duration: 300ms; right: 0px;">
                                        <a class="dropdown-item active" href="{{ url('locale/en') }}">English</a>
                                        <a class="dropdown-item" href="{{ url('locale/bn') }}">বাংলা</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="masthead">
                <div class="bg-secondary-gray-800" id="top_search">
                    <div class="container pb-2"> <!--  pt-3 pt-md-4 pb-3 pb-md-5 -->
                        <div class="d-flex align-items-center position-relative flex-wrap sm-menu">
                            <div class="offcanvas-toggler mr-4">
                                <a id="sidebarNavToggler3" href="javascript:{};" role="button" class="cat-menu">
                                {{-- <a id="sidebarNavToggler3" href="javascript:;" role="button" class="cat-menu" aria-controls="sidebarContent2" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent2" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 100 }' data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft" data-unfold-duration="100"> --}}
                                    <svg width="20px" height="18px">
                                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,-0.000 L20.000,-0.000 L20.000,2.000 L-0.000,2.000 L-0.000,-0.000 Z" />
                                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,8.000 L15.000,8.000 L15.000,10.000 L-0.000,10.000 L-0.000,8.000 Z" />
                                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M-0.000,16.000 L20.000,16.000 L20.000,18.000 L-0.000,18.000 L-0.000,16.000 Z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="site-branding pr-7">
                                <a href="{{ url('/') }}" class="d-block mb-2 mt-sm-2">
                                    <img class="d-block d-sm-none" src="{{ asset('assets/images/logos/boiferry-text-white.svg') }}" alt="" height="40px">
                                    <img class="d-none d-sm-block" src="{{ asset('assets/images/logos/boiferry-main-color-1.svg') }}" alt="" width="150px">
                                </a>
                            </div>
                            <div class="site-search ml-xl-0 ml-md-auto w-r-100 flex-grow-1 mr-md-5 mt-2 mt-md-0 order-1 order-md-0">
                                <form class="form-inline my-2 my-xl-0" action="{{ url('search') }}">
                                    <div class="input-group input-group-borderless w-100">
                                        <input type="search" name="q" id="search_text" autocomplete="off" class="form-control border-left rounded-left-1 rounded-left-xl-0 px-3" placeholder="{{ __('web.Search for books by keyword') }}" aria-label="">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary-green px-3 py-2" type="submit"><i class="mx-1 fas fa-search text-white"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex ml-auto align-items-center">
                                @if($user)
                                <a id="sidebarNavToggler" href="{{ url('my-account') }}">
                                    <div class="d-flex align-items-center text-white font-size-2 text-lh-sm">
                                        <i class="fal fa-user font-size-6"></i>
                                        <div class="ml-2 d-none d-lg-block">
                                            <span class="text-secondary-gray-1080 font-size-1">{{ mb_strimwidth($user->name, 0, 12, '...') }}</span>
                                            <div class="">{{ __('web.My Account') }}</div>
                                        </div>
                                    </div>
                                </a>
                                @else
                                <a id="sidebarNavToggler" href="{{ url('login') }}">
                                    <div class="d-flex align-items-center text-white font-size-2 text-lh-sm">
                                        <i class="fal fa-user font-size-6"></i>
                                        <div class="ml-2 d-none d-lg-block">
                                            <span class="text-secondary-gray-1080 font-size-1">{{ __('web.Sign In') }}</span>
                                            <div class="">{{ __('web.My Account') }}</div>
                                        </div>
                                    </div>
                                </a>
                                @endif

                                {{-- d-none d-lg-block --}}
                                <a id="sidebarNavToggler1" href="javascript:;" role="button" class="ml-4 " aria-controls="sidebarContent1" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent1" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 500}' data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                                    <div class="d-flex align-items-center text-white font-size-2 text-lh-sm position-relative">
                                        <span id="cart_count" class="position-absolute animated bg-primary-green cart-count rounded-circle d-flex align-items-center justify-content-center text-white font-size-n10 left-0 top-0 total_item">0</span>
                                        <i id="cart_icon" class="fal fa-shopping-bag animated font-size-6"></i>
                                        <div class="ml-2 d-none d-lg-block">
                                            <span class="text-secondary-gray-1080 font-size-1">{{ __('web.My Cart') }}</span>
                                            <div class="total_amount"></div>
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
                                @stack('top_menu')
                            </div>
                            <div class="secondary-navigation">
                                <ul class="nav">
                                    <li class="nav-item"><a href="https://quiz.boiferry.com/" class="nav-link mx-2 px-0 py-3 font-size-2 font-weight-medium" style="color: orange;">কুইজ প্রতিযোগিতা</a></li>
                                    <li class="nav-item"><a href="{{ url('books?type=discount') }}" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">{{ __('web.Discounted Books') }}</a></li>
                                    <li class="nav-item"><a href="{{ url('my-account/subscription') }}" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">{{ __('web.Subscription') }}</a></li>
                                    <li class="nav-item"><a href="{{ url('gift-card') }}" class="nav-link link-black-100 mx-2 px-0 py-3 font-size-2 font-weight-medium">{{ __('web.Gift Card') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <style>
            #countdown{
                position: fixed;
                right: 30px;
                background: white;
                text-align: center;
                border: 5px solid #0c497c;
                padding: 8px;
                top: 200px;
                z-index: 1500;
                color: black;
            }
        </style>

        <a id="countdown" href="https://quiz.boiferry.com/">
            <div style="font-size: 18px;">দিন বাকি</div>
            <div class="days" style="font-size: 35px; font-weight: bold; color: red;">30</div>
            <div style="font-size: 18px;">সময় বাকি</div>
            <div class="time" style="font-size: 18px; font-weight: bold;">00:00:00</div>
        </a>

        @yield('content')

        <footer class="site-footer_v2">
            <div class="space-top-3 bg-gray-850">
                <div class="pb-0 space-bottom-lg-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3 mb-lg-0 mobile-text-center">
                                <img src="{{ asset('assets/images/logos/boiferry-main-white.svg') }}" alt="" width="170px">
                                <address class="font-size-2 mb-5 mt-3">
                                    <span class="mb-2 font-weight-normal text-gray-450">
                                        {!! __('web.full_address') !!}
                                    </span>
                                </address>
                                <div class="mb-4 h-white">
                                    <a href="mailto:info@boiferry.com" class="font-size-2 d-block text-gray-450 mb-1">info@boiferry.com</a>
                                    <a href="tel:+8809638112112" class="font-size-2 d-block text-gray-450">09638112112</a>
                                </div>
                                <ul class="list-unstyled mb-0 d-flex justify-content-sm-center justify-content-md-start mb-sm-3">
                                    <li class="h-white btn pl-0">
                                        <a class="text-gray-450" href="https://www.instagram.com/boiferry/">
                                            <span class="fab fa-instagram"></span>
                                        </a>
                                    </li>
                                    <li class="h-white btn">
                                        <a class="text-gray-450" href="https://www.facebook.com/boiferrybd/">
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
                            <div class="col-lg-2 mb-lg-0 col-4 mobile-text-center">
                                <h4 class="font-size-3 font-weight-medium mb-2 mb-xl-5 pb-xl-1 text-white">{{ __('web.My Account') }}</h4>
                                <ul class="list-unstyled mb-0">
                                    @if (auth()->guest())
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('login') }}">{{ __('web.Login') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('register') }}">{{ __('web.Register') }}</a>
                                    </li>
                                    @else
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('logout') }}">{{ __('web.Log out') }}</a>
                                    </li>
                                    @endif
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('my-account') }}">{{ __('web.My Account') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('my-account/orders') }}">{{ __('web.Order History') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('my-account/wishlist') }}">{{ __('web.My Wishlist') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('my-account/orders') }}">{{ __('web.Order Tracking') }}</a>
                                    </li>
                                     <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ route('seller.seller_home') }}">{{ __('web.Seller Center') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-2 mb-lg-0 col-4 mobile-text-center">
                                <h4 class="font-size-3 font-weight-medium mb-2 mb-xl-5 pb-xl-1 text-white">{{ __('web.Policy') }}</h4>
                                <ul class="list-unstyled mb-0">
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/return-policy') }}">{{ __('web.Return Policy') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/refund-policy') }}">{{ __('web.Refund Policy') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/support-policy') }}">{{ __('web.Support Policy') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/terms-of-service') }}">{{ __('web.Terms of Service') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/seller-policy') }}">{{ __('web.Seller Policy') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('legal/privacy-policy') }}">{{ __('web.Privacy Policy') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-2 mb-lg-0 col-4 mobile-text-center">
                                <h4 class="font-size-3 font-weight-medium mb-2 mb-xl-5 pb-xl-1 text-white">{{ __('web.Information') }}</h4>
                                <ul class="list-unstyled mb-0">
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('books') }}">{{ __('web.All Books') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('about-us') }}">{{ __('web.About Us') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('contact-us') }}">{{ __('web.Contact Us') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('become-a-seller') }}">{{ __('web.Become a Seller') }}</a>
                                    </li>
                                    <li class="h-white pb-2">
                                        <a class="font-size-2 text-gray-450 widgets-hover transition-3d-hover" href="{{ url('support') }}">{{ __('web.Support') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mobile-text-center">
                                <div class="text-gray-450 mb-4 mt-sm-2">
                                    {!! __('web.footer') !!}
                                </div>
                            </div>
                            <div class="col-md-12 mt-4 text-center">
                                <img class="d-none d-lg-block d-xl-block" src="{{ asset('assets/images/sslommerz-pc.webp') }}" width="100%" alt="">
                                <img class="d-lg-none" src="{{ asset('assets/images/sslommerz-mobile.webp') }}" width="100%" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-1 border-top border-gray-750">
                    <div class="container">
                        <div class="d-lg-flex text-center text-lg-left justify-content-between align-items-center">
                            <p class="mb-4 mb-lg-0 font-size-2 text-gray-450">©{{ e2b(date('Y')) }} {{ __('web.copyright') }}</p>
                            <div class="ml-auto d-lg-flex justify-content-xl-end align-items-center">
                                <p class="mb-4 mb-lg-0 font-size-2 text-gray-450">{{ __('web.dev') }}</p>
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
                                <span aria-hidden="true">{{ __('web.Close') }} <i class="fas fa-times ml-2"></i></span>
                            </button>
                        </div>


                        <div class="u-sidebar__body">
                            <div class="u-sidebar__content u-header-sidebar__content">

                                <header class="border-bottom px-4 px-md-6 py-4">
                                    <h2 class="font-size-3 mb-0 d-flex align-items-center"><i class="flaticon-icon-126515 mr-3 font-size-5"></i>{{ __('web.Your shopping cart') }} (<span class="total_item">0</span>)</h2>
                                </header>
                                <div id="cart_items">
                                    
                                </div>
                                
                                <div class="px-4 py-5 px-md-6 d-flex justify-content-between align-items-center font-size-3">
                                    <h4 class="mb-0 font-size-3">{{ __('web.Subtotal') }}:</h4>
                                    <div class="font-weight-medium total_amount">0</div>
                                </div>
                                <div class="px-4 mb-8 px-md-6">
                                    <a href="{{ url('/cart') }}" class="btn btn-block py-4 rounded-0 btn-outline-dark mb-4">{{ __('web.View Cart') }}</a>
                                    <a href="{{ url('/checkout') }}" class="btn btn-block py-4 rounded-0 btn-dark">{{ __('web.Checkout') }}</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </aside>

    </div>


    <nav class="sidebar">
        <div class="text"> 
            {{ __('web.Menu') }}
            <button type="button" class="close sidebar-close">
                <span aria-hidden="true"><i class="fas fa-times ml-2"></i></span>
            </button>
        </div>
        @stack('mobile_menu')
    </nav>
    <div class="sidebar-overlay"></div>

    <a href="javascript:;" class="floating-cart" role="button" class="ml-4 " aria-controls="sidebarContent1" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent1" data-unfold-type="css-animation" data-unfold-overlay='{"className": "u-sidebar-bg-overlay","background": "rgba(0, 0, 0, .7)","animationSpeed": 500}' data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight" data-unfold-duration="300">
        <i class="fal fa-shopping-bag animated font-size-12"></i>
        <div class="floating-cart-count">0</div>
    </a>

    @if($popup)
    <div class="modal modal-pu fade" id="modal-popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered animated" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" onclick="hidePopUp();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($popup->link)
                    <a href="{{ $popup->link }}"><img src="{{ showImage($popup->image, 'md') }}" alt=""></a>
                    @else
                    <img src="{{ showImage($popup->image, 'md') }}" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="{{ asset(mix('assets/web/js/web.js')) }}"></script>
    @stack('scripts')
    <script async>
        setTimeout(function(){$(".js-slick-carousel").css("visibility", "visible");}, 100);
        
        @stack('_scripts')
        $(document).ready(function() {
            @if(!request()->month)
            $("#loader").hide();
            @endif  
        });

        @if($popup)
            function createCookie(name,value,seconds) {
                var date = new Date();
                date.setTime(date.getTime()+seconds);
                var expires = "; expires="+date.toGMTString();
                document.cookie = name+"="+value+expires+"; path=/";
            }

            function readCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            function hidePopUp(){
                createCookie('hide_popp','yes', 1000*60*30); // 30 Minute
                $("#modal-popup").modal('hide');
            }

            $('#modal-popup').on('show.bs.modal', function (e) {$('#modal-popup .modal-dialog').addClass('jello').removeClass('zoomOut');});
            $('#modal-popup').on('hide.bs.modal', function (e) {$('#modal-popup .modal-dialog').addClass('zoomOut').removeClass('jello');});
            if(!readCookie('hide_popp')){
                setTimeout(function(){$("#modal-popup").modal('show');}, 1000 * {{ $popup->delay }});
            }
            @endif  

        let flatpickrInstance;
        flatpickr("#dob",{dateFormat: 'Y-m-d'});
        function dobPopup(){
            if(localStorage.getItem('dob_deny')!='true'){
                Swal.fire({
                    title: 'Share your birthdate and get 10TK OFF your first order.',
                    imageUrl: '{{ asset('assets/images/gift-1.svg') }}',
                    imageWidth: 150,
                    html: '<input class="swal2-input" id="dob">',
                    stopKeydownPropagation: true,
                    showDenyButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Share Birthdate',
                    denyButtonText: 'Not Interested',
                    preConfirm: () => {
                        if (!flatpickrInstance.selectedDates[0]) {
                            Swal.showValidationMessage(`Birthday can't be empty!`)
                        }else if (flatpickrInstance.selectedDates[0] > new Date()) {
                            Swal.showValidationMessage(`Birthday can't be in the future!`)
                        }else{
                            $.ajax({
                                method: "GET",
                                url: '{{ url('my-account/profile') }}?dob=' + $("#dob").val(),
                                success: function(data){
                                    localStorage.setItem('dob_deny', true);
                                    Swal.fire('Thanks :)','Enjoy  your 10 tk discount.','success');
                                },error: function(data){
                                    Swal.fire('Ops :(','Something went wrong','error');
                                }
                            });
                        }
                    },
                    willOpen: () => {
                        flatpickrInstance = flatpickr(
                            Swal.getPopup().querySelector('#dob')
                        )
                    },
                    preDeny: () => {
                        localStorage.setItem('dob_deny', true);
                    }
                });
            }
        }

        @if($user && empty($user->dob))
        /*setTimeout(function(){
            dobPopup();
        }, 1000*15);*/
        @endif

        $('#sidebarNavToggler3').click(function(){
            $(this).toggleClass("click");
            $(".sidebar-overlay").show();
            $("body").css('overflow','hidden');
            $('.sidebar').toggleClass("show");
        });


        $('.sidebar ul li a').click(function(){
            var id = $(this).attr('id');
            $("nav ul li ul.show").not('ul.item-show-'+id).toggleClass("show");
            $('nav ul li ul.item-show-'+id).toggleClass("show");
            $('nav ul li #'+id+' span').toggleClass("rotate");
        });

        $('nav ul li').click(function(){
            $(this).addClass("active").siblings().removeClass("active");
        });

        $(".sidebar-overlay, .sidebar-close").click(function(){
            $(".sidebar-overlay").hide();
            $('.sidebar').toggleClass("show");
            $("body").css('overflow','auto');
        });

        var countDownDate = new Date("Jan 16, 2023 21:00:00").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            // document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            console.log([days,hours,minutes,seconds]);
            $("#countdown .days").html(days);
            $("#countdown .time").html(`${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`);
            if (distance < 0) {
                clearInterval(x);
                $("#countdown").hide();
            }
        }, 1000);
    </script>

    {{-- top to bottom --}}
    <script async>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '547440866499152');fbq('track', 'PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=547440866499152&ev=PageView&noscript=1"/></noscript>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-17S7ZQJEP3"></script>
    <script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'G-17S7ZQJEP3');</script>
    <script async>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-NDN6B4C');</script>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NDN6B4C" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-207938615-1"></script>
    <script async>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-207938615-1');</script>
    {{-- top to bottom --}}

    <script defer>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "106444285040680");
      chatbox.setAttribute("attribution", "biz_inbox");
      window.fbAsyncInit = function() {FB.init({xfbml: true,version: 'v12.0'});};
      (function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
    </script>
    {{-- <script src="https://code-eu1.jivosite.com/widget/zdeeLVZe4L" async></script> --}}
</body>
</html>
