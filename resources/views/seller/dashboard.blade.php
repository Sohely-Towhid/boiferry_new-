{{-- BTL Template - Do not delete --}}
@extends('layouts.seller')
@section('title','Dashboard')
@section('content')
@php
$user = Auth::user();
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <!--begin::Top-->
                <div class="d-flex">
                    <!--begin::Pic-->
                    <div class="flex-shrink-0 mr-7">
                        <div class="symbol symbol-50 symbol-lg-120 symbol-circle">
                            <img alt="Store Logo" src="{{ ($user->vendor->logos) ? showImage(@$user->vendor->logos[1],'lg') : asset('assets/images/default-logo.jpg') }}" style="width: 200px;">
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin: Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                            <!--begin::User-->
                            <div class="mr-3">
                                <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $user->vendor->name }}</a>
                                <!--begin::Contacts-->
                                <div class="d-flex flex-wrap my-2">
                                    <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                    <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"></path>
                                                <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"></circle>
                                            </g>
                                        </svg>
                                    </span>{{ $user->vendor->email }}</a>
                                    <a href="#" class="text-muted text-hover-primary font-weight-bold">
                                    <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"></path>
                                            </g>
                                        </svg>
                                    </span>{{ $user->vendor->address }}</a>
                                </div>
                                <!--end::Contacts-->
                            </div>
                            <!--begin::User-->
                            <!--begin::Actions-->
                            <div class="my-lg-0 my-1">
                                <a href="{{ route('book_home').'/shop/'.$user->vendor->slug }}" class="btn btn-sm btn-light-primary font-weight-bolder mr-2">Other Shop</a>
                                <a href="{{ route('book_home').'/shop/'.$user->vendor->slug }}" class="btn btn-sm btn-primary font-weight-bolder">Book Shop</a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Content-->
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <!--begin::Description-->
                            <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">
                                @if($user->vendor->rating> 0 && $user->vendor->rating<=3)
                                {!! htmlStar($user->vendor->rating,'fa-2x text-danger') !!}
                                @elseif($user->vendor->rating>3)
                                {!! htmlStar($user->vendor->rating,'fa-2x text-warning') !!}
                                @else
                                <h3>No Rating Yet!</h3>
                                @endif
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Top-->
                <!--begin::Separator-->
                <div class="separator separator-solid my-7"></div>
                <!--end::Separator-->
                <!--begin::Bottom-->
                <div class="d-flex align-items-center flex-wrap">
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-warning svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5.94290508,4 L18.0570949,4 C18.5865712,4 19.0242774,4.41271535 19.0553693,4.94127798 L19.8754445,18.882556 C19.940307,19.9852194 19.0990032,20.9316862 17.9963398,20.9965487 C17.957234,20.9988491 17.9180691,21 17.8788957,21 L6.12110428,21 C5.01653478,21 4.12110428,20.1045695 4.12110428,19 C4.12110428,18.9608266 4.12225519,18.9216617 4.12455553,18.882556 L4.94463071,4.94127798 C4.97572263,4.41271535 5.41342877,4 5.94290508,4 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M7,7 L9,7 C9,8.65685425 10.3431458,10 12,10 C13.6568542,10 15,8.65685425 15,7 L17,7 C17,9.76142375 14.7614237,12 12,12 C9.23857625,12 7,9.76142375 7,7 Z" fill="#000000"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a href="{{ url('invoice?status=pending') }}" class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Pending Orders</span>
                            <span class="font-weight-bolder font-size-h5">{{ $order['pending'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,8 L19,8 C20.1045695,8 21,8.8954305 21,10 L21,20 C21,21.1045695 20.1045695,22 19,22 L5,22 C3.8954305,22 3,21.1045695 3,20 L3,10 C3,8.8954305 3.8954305,8 5,8 Z M12,20 C14.7614237,20 17,17.7614237 17,15 C17,12.2385763 14.7614237,10 12,10 C9.23857625,10 7,12.2385763 7,15 C7,17.7614237 9.23857625,20 12,20 Z M11.5730613,13.5616319 L12,11 L12.4269387,13.5616319 C13.0473823,13.7455122 13.5,14.3198988 13.5,15 C13.5,15.8284271 12.8284271,16.5 12,16.5 C11.1715729,16.5 10.5,15.8284271 10.5,15 C10.5,14.3198988 10.9526177,13.7455122 11.5730613,13.5616319 Z" fill="#000000"/>
                                        <path d="M14,6 L14,8 L10,8 L10,6 L7.80277564,6 C6.67650121,6 5.62474465,5.43711697 5,4.5 L4.5182334,3.7773501 C4.36505717,3.54758575 4.4271441,3.23715108 4.65690845,3.08397485 C4.73904221,3.02921901 4.83554605,3 4.93425855,3 L19.0657415,3 C19.3418838,3 19.5657415,3.22385763 19.5657415,3.5 C19.5657415,3.59871249 19.5365224,3.69521634 19.4817666,3.7773501 L19,4.5 C18.3752554,5.43711697 17.3234988,6 16.1972244,6 L14,6 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a href="{{ url('invoice?status=packed') }}" class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Packed Orders</span>
                            <span class="font-weight-bolder font-size-h5">{{ $order['packed'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6.98113892,21.5 L7.61810208,21.5 C7.84357761,21.5 8.04115745,21.3490893 8.10048399,21.1315587 L9.37729932,16.4497595 C9.44617559,16.1972133 9.69816937,16.0405293 9.95512991,16.0904778 C11.3596584,16.3634926 12.3746151,16.5 13,16.5 C13.646503,16.5 15.0010968,16.3541177 17.0637814,16.0623532 L17.0637875,16.0623965 C17.3131816,16.0271199 17.5498754,16.1828763 17.6161485,16.4258779 L18.899516,21.1315587 C18.9588425,21.3490893 19.1564224,21.5 19.3818979,21.5 L20.0384026,21.5 C20.2990829,21.5 20.5160222,21.2997228 20.5368102,21.0398726 L21.1544815,13.3189809 C21.3306498,11.1168774 19.6883048,9.18890717 17.4862013,9.01273889 C17.3800862,9.00424968 17.2736745,9 17.1672204,9 L13,9 C12.0256112,6.96792142 11.1922779,5.63458808 10.5,5 C10.1827335,4.70917234 8.36084967,3.94216891 5.03434861,2.69898968 L5.03438003,2.69890562 C4.87913228,2.64088647 4.7062453,2.71970582 4.64822614,2.87495357 C4.62696245,2.93185098 4.62346541,2.99386164 4.63819725,3.05278899 L4.92939183,4.21785549 C4.97292798,4.39200007 4.919759,4.57611822 4.79008912,4.70024499 C4.13636504,5.32602378 3.70633533,5.75927545 3.5,6 C3.28507393,6.25074708 2.97597493,7.00745907 2.57270301,8.27013596 L2.5727779,8.27015988 C2.52651585,8.4150101 2.54869436,8.57304154 2.6330412,8.69956179 L3.23554277,9.60331416 C3.38359021,9.82538532 3.67995409,9.89202755 3.9088158,9.75471052 L4.75,9.25 C5.15859127,9.00484524 5.68855714,9.13733671 5.9337119,9.54592798 C6.00837879,9.67037279 6.05044776,9.81164184 6.05602542,9.95666096 L6.48150833,21.0192166 C6.49183398,21.2876836 6.71247339,21.5 6.98113892,21.5 Z" fill="#000000"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a href="{{ url('invoice?status=shipping') }}" class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">in Shipping</span>
                            <span class="font-weight-bolder font-size-h5">{{ $order['shipping'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a href="{{ url('/book?type=stockout') }}" class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">No In Stock (Book)</span>
                            <span class="font-weight-bolder font-size-h5">{{ $stock['book'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M4,7 L20,7 L20,19.5 C20,20.3284271 19.3284271,21 18.5,21 L5.5,21 C4.67157288,21 4,20.3284271 4,19.5 L4,7 Z M10,10 C9.44771525,10 9,10.4477153 9,11 C9,11.5522847 9.44771525,12 10,12 L14,12 C14.5522847,12 15,11.5522847 15,11 C15,10.4477153 14.5522847,10 14,10 L10,10 Z" fill="#000000"/>
                                        <rect fill="#000000" opacity="0.3" x="2" y="3" width="20" height="4" rx="1"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a href="{{ url('/product?type=stockout') }}" class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">No In Stock (Product)</span>
                            <span class="font-weight-bolder font-size-h5">{{ $stock['product'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                        <path d="M16.7689447,7.81768175 C17.1457787,7.41393107 17.7785676,7.39211077 18.1823183,7.76894473 C18.5860689,8.1457787 18.6078892,8.77856757 18.2310553,9.18231825 L11.2310553,16.6823183 C10.8654446,17.0740439 10.2560456,17.107974 9.84920863,16.7592566 L6.34920863,13.7592566 C5.92988278,13.3998345 5.88132125,12.7685345 6.2407434,12.3492086 C6.60016555,11.9298828 7.23146553,11.8813212 7.65079137,12.2407434 L10.4229928,14.616916 L16.7689447,7.81768175 Z" fill="#000000" fill-rule="nonzero"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <a class="d-flex flex-column" href="{{ url('invoice?status=complete') }}">
                            <span class="text-dark-75 font-weight-bolder font-size-sm">Complete</span>
                            <span class="font-weight-bolder font-size-h5">{{ $order['complete'] }}</span>
                        </a>
                    </div>
                    <!--end: Item-->
                </div>
                <!--end::Bottom-->
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
@endpush