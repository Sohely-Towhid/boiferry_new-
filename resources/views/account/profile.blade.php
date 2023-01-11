@extends('layouts.books')
@section('title',__('web.Account Details'))
@section('content')
@php 
$user = Auth::user();
@endphp
<main id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3 border-right">
                <h6 class="font-weight-medium font-size-7 pt-5 pt-lg-8  mb-5 mb-lg-7">{{ __('web.Account Details') }}</h6>
                <div class="tab-wrapper">
                    @include('account.menu')
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
                        <div class="pt-5 pt-lg-8 pl-md-5 pl-lg-9 space-bottom-2 space-bottom-lg-3 mb-xl-1">
                            <h6 class="font-weight-medium font-size-7 ml-lg-1 mb-lg-8 pb-xl-1">{{ __('web.Account Details') }}</h6>
                            @include('msg')
                            <form action="" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="font-weight-medium font-size-22 mb-4 pb-xl-1">{{ __('web.Edit Account') }}</div>
                                    </div>
                                    <x-form::input column="4" name="name" title="{{ __('web.Full Name') }}" :required="true" type="text" value="{{ $user->name }}" />
                                    <x-form::input column="4" name="email" title="{{ __('web.Email') }}" disabled="yes" :required="true" type="email" value="{{ $user->email }}" />
                                    <x-form::input column="4" name="mobile" title="{{ __('web.Mobile') }}" disabled="yes" :required="true" type="tel" value="{{ $user->mobile }}" />
                                    <div class="col-md-12 mt-3 pt-3 border-top">
                                        <div class="font-weight-medium font-size-22 mb-4 pb-xl-1">{{ __('web.Password Change') }}</div>
                                    </div>
                                    <x-form::input column="4" name="old_password" title="{{ __('web.Current Password') }}" :required="false" type="password" value="" />
                                    <x-form::input column="4" name="password" title="{{ __('web.New Password') }}" :required="false" type="password" value="" />
                                    <x-form::input column="4" name="password_confirmation" title="{{ __('web.Confirm new password') }}" :required="false" type="password" value="" />
                                    <div class="col-md-3 mt-3">
                                        <button type="submit" class="btn btn-wide btn-dark text-white rounded-0 transition-3d-hover height-60 width-390">{{ __('web.Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection