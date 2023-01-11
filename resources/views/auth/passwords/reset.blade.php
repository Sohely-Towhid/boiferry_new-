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
    <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="pb-13 pt-lg-0 pt-5">
            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">পাসওয়ার্ড রিসেট করুন</h3>
        </div>
        @include('msg')
        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="email">{{ __('E-Mail Address') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="email" name="email" required value="{{ $email ?? old('email') }}" />
        </div>

        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="password">{{ __('New Password') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="password" name="password" required value="" />
        </div>

        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="password" name="password_confirmation" required value="" />
        </div>
        
        <div class="pb-lg-0 pb-5">
            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">{{ __('Reset Password') }}</button>
            <div>&nbsp;</div>
        </div>
    </form>
</div>
@endif

@if($layout=='books')
<div class="page-header border-bottom">
    <div class="container">
        <div class="d-md-flex justify-content-between align-items-center py-4">
            <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">{{ __('Reset Password') }}</h1>
            <nav class="woocommerce-breadcrumb font-size-2">
                <a href="{{ url('/') }}" class="h-primary">Home</a>
                <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('Reset Password') }}</span>
            </nav>
        </div>
    </div>
</div>
<div class="container mt-10 mb-10">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card-body pt-10 pb-10">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
