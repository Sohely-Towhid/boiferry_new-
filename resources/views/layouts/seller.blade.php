@php $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - @yield('title','Admin')</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="{{ asset('assets/admin/css/admin.css') }}" rel="stylesheet" type="text/css" />
        @stack('style')
        <style>
        @stack('_style')
        </style>

        <link rel="shortcut icon" href="images/favicon.ico" />
    </head>

    <body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        
        <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
            <a href="{{ url('') }}">
                Seller Center
            </a>
            <div class="d-flex align-items-center">
                <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle"><span></span></button>
                {{-- <button class="btn p-0 burger-icon ml-5" id="kt_header_mobile_toggle"><span></span></button> --}}
                
                <button class="btn btn-hover-text-primary p-0 ml-3" id="kt_header_mobile_topbar_toggle">
                    <span class="svg-icon svg-icon-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                    </span>
                </button>

            </div>
        </div>

        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-row flex-column-fluid page">
                <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
                    <div class="brand flex-column-auto" id="kt_brand">
                        <a href="{{ url('') }}" class="brand-logo">
                            <h1>Seller Center</h1>
                            {{-- <img alt="Logo" src="assets/images/logo-1.svg" class="h-30px" /> --}}
                        </a>
                        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                            <span class="svg-icon svg-icon svg-icon-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22 11.5C22 12.3284 21.3284 13 20.5 13H3.5C2.6716 13 2 12.3284 2 11.5C2 10.6716 2.6716 10 3.5 10H20.5C21.3284 10 22 10.6716 22 11.5Z" fill="black" />
                                        <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M14.5 20C15.3284 20 16 19.3284 16 18.5C16 17.6716 15.3284 17 14.5 17H3.5C2.6716 17 2 17.6716 2 18.5C2 19.3284 2.6716 20 3.5 20H14.5ZM8.5 6C9.3284 6 10 5.32843 10 4.5C10 3.67157 9.3284 3 8.5 3H3.5C2.6716 3 2 3.67157 2 4.5C2 5.32843 2.6716 6 3.5 6H8.5Z" fill="black" />
                                    </g>
                                </svg>
                            </span>
                        </button>
                    </div>


                    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
                        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
                            <ul class="menu-nav">
                            @include('layouts.seller-menu')
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    <div id="kt_header" class="header header-fixed">
                        <div class="container-fluid d-flex align-items-stretch justify-content-between">
                            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                               <form action="{{ url('search') }}" method="GET">
                                    <div class="input-group input-group-sm bg-gray border-0 rounded pt-6" style="min-width: 300px;">
                                        <input type="text" class="form-control form-control-solid" name="q" placeholder="Search...">
                                    </div>
                                </form>
                            </div>
                            <div class="topbar">
                            
                                <div class="dropdown mr-1">
                                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                                        <div class="btn btn-icon btn-clean btn-dropdown btn-lg">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                        <ul class="navi navi-hover py-4">
                                            <li class="navi-item">
                                                <a href="{{ url('/profile') }}" class="navi-link"><span class="navi-text">Profile</span></a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="{{ url('logout') }}" class="navi-link"><span class="navi-text">Logout</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        @yield('content')
                    </div>

                    <div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="text-dark order-2 order-md-1">
                                <span>Made with <i class="fa fa-heart"></i> by Bluedot Technology Ltd</span>
                            </div>
                            <div class="nav nav-dark">
                                <a href="{{ url('/help') }}" target="_blank" class="nav-link pl-0 pr-2">Help</a>
                                <a href="{{ url('/') }}" target="_blank" class="nav-link pr-0">Main Site</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_scrolltop" class="scrolltop">
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                        <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
            </span>
        </div>

        {{-- <script src="{{ asset('assets/keen/js/plugins.bundle.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/plugins.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/bootstrap-datepicker.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/prismjs.bundle.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/scripts.bundle.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/chart.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/fullcalendar.bundle.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/jquery.dataTables.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/keen/js/dataTables.bootstrap5.min.js') }}"></script> --}}
        
        <script src="{{ asset('assets/admin/js/plugins.js') }}"></script>
        <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3E97FF", "secondary": "#E5EAEE", "success": "#08D1AD", "info": "#844AFF", "warning": "#F5CE01", "danger": "#FF3D60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#DEEDFF", "secondary": "#EBEDF3", "success": "#D6FBF4", "info": "#6125E1", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
        {{-- <script src="{{ asset('assets/keen/js/widgets.js') }}"></script> --}}
        
        @stack('scripts')
        <script>
            $.fn.select2.defaults.set("theme", "bootstrap");
            $.ajaxSetup({
                timeout: 60*60*1000,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function __delete(id, url, title, text, success, error){
                if(!title) title = 'Are you sure?';
                if(!text) text = 'You won\'t be able to revert this!';
                if(!success) success = 'Your record has been deleted.';
                if(!error) error = 'You can\'t delete this record!';
                var swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                });

                swalWithBootstrapButtons.fire({
                    title: title,
                    text: text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('/') }}' +  '/' + url + '/' + id,
                            type: 'POST',
                            data: {'_method': 'DELETE'},
                            success: function(result) {
                                swalWithBootstrapButtons.fire('Deleted!',success,'success');
                                window.LaravelDataTables["dataTableBuilder"].ajax.reload( null, false );
                            }, error: function(){
                                swalWithBootstrapButtons.fire('Sorry!',error,'error');
                            }
                        });
                    }
                });
            }
            @stack('_scripts')
        </script>
    </body>
</html>