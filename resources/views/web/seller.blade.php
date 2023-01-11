@extends('layouts.books')
@section('title','Checkout')
@section('content')
@php
$user = Auth::user();
$vendor = App\Models\Vendor::where('user_id',@$user->id)->first();
$page = App\Models\Page::where('slug','become-a-seller')->first();
@endphp
<div class="site-content bg-light overflow-hidden " id="content">
    <div class="col-full container mb-10">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <article id="post-6" class="post-6 page type-page status-publish hentry">
                    <header class="entry-header space-top-2 space-bottom-1 mb-2">
                        <h4 class="entry-title font-size-7 text-center">Become a Seller</h4>
                    </header>
                    @include('msg')
                    <div class="entry-content">
                        <div class="card mb-5">
                            <div class="card-body">
                                {!! $page->description !!}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body pb-1">
                                @if(!$vendor && $user)
                                <div class="text-center mb-4">
                                    <h2>Apply to become a seller</h2>
                                </div>
                                <form action="" method="POST" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            Please read our terms and services before joining our seller team.
                                        </div>
                                    </div>
                                    <x-form::input column="6" name="name" title="Store Name" :required="true" type="text" value="" />
                                    <x-form::input column="3" name="email" title="Store Email" :required="true" type="email" value="" />
                                    <x-form::input column="3" name="mobile" title="Store Mobile" :required="true" type="tel" value="" />
                                    <x-form::textarea column="12" name="address" title="Address" :required="true" type="text" value="" />
                                    <x-form::checkbox column="12" name="book" title="I want to sell Book" :required="false" type="text" value="1" />
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            File must be in JPG, PNG or PDF format.
                                        </div>
                                    </div>
                                    <x-form::input column="2" name="trade_licence" title="Trade Licence" :required="true" type="file" value="" />
                                    <x-form::input column="2" name="nid" title="National ID" :required="true" type="file" value="" />
                                    <x-form::input column="2" name="bank" title="Empty Bank Cheque" :required="false" type="file" value="" />
                                    <x-form::input column="2" name="tin" title="TIN" :required="false" type="file" value="" />
                                    <x-form::input column="2" name="bin" title="BIN" :required="false" type="file" value="" />
                                    <div class="col-md-12">
                                        <button class="btn btn-info">Submit Application</button>
                                    </div>
                                </form>
                                @elseif(!$user)
                                 <div class="alert alert-danger">
                                    <a href="{{ url('login') }}">Login</a> to our website first to become a seller. If you don't have an account with us, please <a href="{{ url('register') }}">register</a>. 
                                </div>
                                @else
                                    @if($vendor->status==1)
                                    <div class="alert alert-success">
                                        You are a seller now. Please login using: <a href="https://seller.boiferry.com/">https://seller.boiferry.com/</a>
                                    </div>
                                    @elseif($vendor->status==2)
                                    <div class="alert alert-danger">
                                        Your application is rejected.
                                    </div>
                                    @else
                                    <div class="alert alert-info">
                                        You are already applied to become a seller. We are working with our business team to make sure everything goes according to our policy. We will send you email about your application status.
                                        <br><br>This may take few business days.
                                    </div>
                                    <div class="text-center">
                                        <img src="{{ asset('assets/images/seller-processing.png') }}" alt="">
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            </main>
        </div>
    </div>
</div>
@endsection

@push('_scripts')

@endpush