@php
$domain = request()->getHost();
$layout = 'books';
if(preg_match("/(seller|management)/", $domain, $domain_match)){
    $layout = 'auth';
}
@endphp
@extends('layouts.'.$layout)

@section('content')
@if($layout=='auth')
<div class="login-form login-signin">
    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},
    <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="pb-13 pt-lg-0 pt-5">
            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">ইমেইল ভ্যারিফাই করুন</h3>
        </div>
        @include('msg')
        <div class="pb-lg-0 pb-5">
            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">{{ __('click here to request another') }}</button>
            <div>&nbsp;</div>
        </div>
    </form>
</div>
@endif

@if($layout=='books')
<div class="page-header border-bottom">
    <div class="container">
        <div class="d-md-flex justify-content-between align-items-center py-4">
            <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">{{ __('Verify Email') }}</h1>
            <nav class="woocommerce-breadcrumb font-size-2">
                <a href="{{ url('/') }}" class="h-primary">Home</a>
                <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('Verify Email') }}</span>
            </nav>
        </div>
    </div>
</div>
<div class="container mt-10 mb-10">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection