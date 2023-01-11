@extends('layouts.books')
@section('title',__('web.Dashboard'))
@section('content')
@php 
$user = Auth::user();
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
                            @if(Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Payment Failed!</strong><br>{!! Session::get('error') !!}
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            <h6 class="font-weight-medium font-size-7 ml-lg-1 mb-lg-8 pb-xl-1">{{ __('web.Dashboard') }}</h6>
                            <div class="ml-lg-1 mb-4">
                                <span class="font-size-22">{{ __('web.Hello') }} {{ $user->name }}</span>
                                <span class="font-size-2"> ({{ __('web.not_name', ['name'=> $user->name]) }} ? <a class="link-black-100" href="{{ url('logout') }}">{{ __('web.Log out') }}</a>)</span>
                            </div>
                            <div class="mb-4">
                                <p class="mb-0 font-size-2 ml-lg-1 text-gray-600">{!! clean(__('web.dashboard_welcome')) !!}</p>
                                {{-- @include('msg') --}}
                            </div>
                            <div class="row no-gutters row-cols-1 row-cols-md-2 row-cols-lg-3">
                                <div class="col">
                                    <div class="border py-6 text-center">
                                        <a href="{{ url('my-account/orders') }}" class="btn  bg-gray-200 rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-shopping-bag fa-fw font-size-10 btn-icon__inner text-primary"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.Orders') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border py-6 text-center">
                                        <a href="{{ url('my-account/orders?type=shipping') }}" class="btn  bg-gray-200 rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-truck-moving fa-fw fa-fw font-size-10 btn-icon__inner text-primary"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.In Shipping') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border border-left-0 py-6 text-center">
                                        <a href="{{ url('my-account/subscription') }}" class="btn bg-gray-200 rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-money-check-alt fa-fw font-size-10 btn-icon__inner text-primary"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.Subscription') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border py-6 text-center">
                                        <a href="{{ url('my-account/profile') }}" class="btn bg-gray-200 rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-user fa-fw font-size-10 btn-icon__inner text-primary"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.Account Details') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border border-left-0 py-6 text-center">
                                        <a href="{{ url('my-account/wishlist') }}" class="btn bg-gray-200  rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-heart font-size-10 btn-icon__inner text-primary pl-1"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.Wishlist') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border border-left-0 py-6 text-center">
                                        <a href="{{ url('my-account/support') }}" class="btn bg-gray-200 rounded-circle px-4 mb-2 my-account-round">
                                            <span class="fa fa-question font-size-10 btn-icon__inner text-primary pl-1"></span>
                                        </a>
                                        <div class="font-size-3 mb-xl-1">{{ __('web.Support') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection