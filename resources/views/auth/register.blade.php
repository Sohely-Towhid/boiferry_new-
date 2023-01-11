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
    <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="pb-13 pt-lg-0 pt-5">
            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">বইফেরিতে রেজিস্টার করুন</h3>
        </div>
        @include('msg')
        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="name">{{ __('auth.Full Name') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="name" name="name" required />
        </div>
        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="email">{{ __('auth.E-Mail Address') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="email" name="email" required />
        </div>
        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark" for="mobile">{{ __('auth.Mobile') }}</label>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="tel" name="mobile" required />
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between mt-n5">
                <label class="font-size-h6 font-weight-bolder text-dark pt-5">{{ __('auth.Password') }}</label>
                <a href="{{ route('password.request') }}" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">{{ __('auth.Forgot Your Password?') }}</a>
            </div>
            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="password" name="password" required autocomplete="off" />
        </div>
        <div class="pb-lg-0 pb-5">
            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">{{ __('auth.Register') }}</button>
            <div>&nbsp;</div>
        </div>
    </form>
</div>
@endif

@if($layout=='books')
<div class="container">
    <div class="row">
        <div class="col-md-6" style="background-color: #f5f5f5;">
            <img src="{{ asset('assets/images/register-1.webp') }}" alt="" width="100%">
        </div>
        <div class="col-md-6">
            <div class="row justify-content-center mt-10 mb-10">
                <div class="col-md-8">
                    <div class="mb-4 text-center">
                        <h3>{!! __('web.Register with') !!}</h3>
                        <span class="d-block my-4 text-muted"> {{ __('web.or register with') }}</span>
                        <div class="social-login text-center">
                            <a href="{{ url('auth/facebook') }}" class="facebook">
                                <span class="fab fa-facebook mr-3"></span>
                            </a>
                            <a href="{{ url('auth/google') }}" class="google">
                                <span class="fab fa-google mr-3"></span>
                            </a>
                        </div>
                    </div>
                    @include('msg')
                    <form action="{{ route('register') }}" method="post">
                        @csrf
                        <div class="form-group first">
                            <label for="name">{{ __('web.Full Name') }}</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('auth.E-Mail Address') }}</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mobile">{{ __('web.Mobile') }}</label>
                            <input type="tel" class="form-control" name="mobile" id="mobile" value="{{ old('mobile') }}" required>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group last mb-4">
                            <label for="password">{{ __('web.Password') }}</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-flex mb-5 align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tos" id="tos" {{ old('tos') ? 'checked' : '' }}>
                                <label class="form-check-label" for="tos">
                                    {!! __('web.tos', ['tos'=>url('legal/terms-of-service'),'pp'=>url('legal/privacy-policy'),'rp'=>url('legal/refund-policy')]) !!}
                                </label>
                            </div>
                        </div>
                        <input type="submit" value="{{ __('auth.Register') }}" class="btn text-white btn-block btn-primary">

                        <div class="text-center mt-5">{{ __('web.Have an account?') }} <a href="{{ route('login') }}">{{ __('web.Sign In') }}</a></div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="page-header border-bottom">
    <div class="container">
        <div class="d-md-flex justify-content-between align-items-center py-4">
            <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">{{ __('Register') }}</h1>
            <nav class="woocommerce-breadcrumb font-size-2">
                <a href="{{ url('/') }}" class="h-primary">Home</a>
                <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('Register') }}</span>
            </nav>
        </div>
    </div>
</div>
<div class="container mt-10 mb-10">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card">
                <div class="card-body pt-10 pb-10">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
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
                            <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('Mobile') }}</label>
                            <div class="col-md-6">
                                <input id="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" required>
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endif
@endsection

