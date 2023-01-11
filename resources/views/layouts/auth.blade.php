@php $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">
        <title>{{ config('app.name', 'Laravel') }} - @yield('title','Admin')</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="{{ mix('assets/admin/css/admin.css') }}" rel="stylesheet" type="text/css" />

        @stack('style')
        @stack('styles')
        <style>
        @stack('_style')
        </style>
        <link rel="shortcut icon" href="images/favicon.ico" />
    </head>

    <body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        
        <div class="d-flex flex-column flex-root">
            <!--begin::Login-->
            <div class="login login-2 login-signin-on d-flex flex-column flex-column-fluid bg-white position-relative overflow-hidden" id="kt_login">
                <!--begin::Header-->
                <div class="login-header py-10 flex-column-auto">
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                        <!--begin::Logo-->
                        <a href="#" class="flex-column-auto py-5 py-md-0">
                            <img src="{{ asset('assets/images/logos/boiferry-main-color.svg') }}" alt="logo" class="h-50px" height="50px;" />
                        </a>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center">
                    <div class="container row">
                        <div class="col-lg-6">
                            @yield('content')
                        </div>
                        <div class="col-lg-6 bgi-size-contain bgi-no-repeat bgi-position-y-center bgi-position-x-center min-h-150px mt-10 m-md-0" style="background-image: url({{ asset('assets/images/login.svg') }}); background-position: center center; background-repeat: no-repeat;"></div>
                    </div>
                </div>
                <!--end::Body-->

                <!--begin::Footer-->
                <div class="login-footer py-10 flex-column-auto">
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                        <div class="font-size-h6 font-weight-bolder order-2 order-md-1 py-2 py-md-0">
                            <span class="text-muted font-weight-bold mr-2">2021©</span>
                            <a href="" target="_blank" class="text-dark-50 text-hover-primary">Winners Bazar. All rights reserved</a>
                        </div>
                        <div class="font-size-h5 font-weight-bolder order-1 order-md-2 py-2 py-md-0">
                            <a href="#" class="text-primary">উইনার্স বাজার</a>
                            <a href="#" class="text-primary ml-10">বইফেরি</a>
                            <a href="#" class="text-primary ml-10">যোগাযোগ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <script src="{{ asset('assets/admin/js/plugins.js') }}"></script>
        <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3E97FF", "secondary": "#E5EAEE", "success": "#08D1AD", "info": "#844AFF", "warning": "#F5CE01", "danger": "#FF3D60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#DEEDFF", "secondary": "#EBEDF3", "success": "#D6FBF4", "info": "#6125E1", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
        @stack('scripts')
    </body>
</html>