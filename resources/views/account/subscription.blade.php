@extends('layouts.books')
@section('title',__('web.Subscription'))
@section('content')
@php 
$user = Auth::user();
$items = App\Models\Setting::where('name', 'like', 'subscription%')->get()->pluck('value','name')->toArray();
@endphp
<style>
@import url(//fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800);
.pricing6 {
  font-family: "Montserrat", sans-serif;
  color: #8d97ad;
  font-weight: 300;
}

.pricing6 h1,
.pricing6 h2,
.pricing6 h3,
.pricing6 h4,
.pricing6 h5,
.pricing6 h6 {
  color: #3e4555;
}

.pricing6 .font-weight-medium {
  font-weight: 500;
}

.pricing6 .bg-light {
  background-color: #f4f8fa !important;
}

.pricing6 h5 {
    line-height: 22px;
    font-size: 18px;
}

.pricing6 .subtitle {
  color: #8d97ad;
  line-height: 24px;
}

.pricing6 .card.card-shadow {
  -webkit-box-shadow: 0px 0px 30px rgba(115, 128, 157, 0.1);
  box-shadow: 0px 0px 30px rgba(115, 128, 157, 0.1);
}

.pricing6 .price-box sup {
  top: -20px;
  font-size: 16px;
}

.pricing6 .price-box .display-5 {
  line-height: 58px;
  font-size: 3rem;
}

.pricing6 .btn-info-gradiant {
    background: #449f3f;
    background: -webkit-linear-gradient(legacy-direction(to right), #449f3f 0%, #277e23 100%);
    background: -webkit-gradient(linear, left top, right top, from(#449f3f), to(#277e23));
    background: -webkit-linear-gradient(left, #449f3f 0%, #277e23 100%);
    background: -o-linear-gradient(left, #449f3f 0%, #277e23 100%);
    background: linear-gradient(to right, #449f3f 0%, #277e23 100%);
}

.pricing6 .btn-info-gradiant:hover {
    background: #277e23;
    background: -webkit-linear-gradient(legacy-direction(to right), #277e23 0%, #449f3f 100%);
    background: -webkit-gradient(linear, left top, right top, from(#277e23), to(#449f3f));
    background: -webkit-linear-gradient(left, #277e23 0%, #449f3f 100%);
    background: -o-linear-gradient(left, #277e23 0%, #449f3f 100%);
    background: linear-gradient(to right, #277e23 0%, #449f3f 100%);
}

.pricing6 .btn-md {
    padding: 15px 45px;
    font-size: 16px;
}

.pricing6 .text-info {
    color: #188ef4 !important;
}

.pricing6 .badge-danger {
    background-color: #ff4d7e;
}

.pricing6 .font-14 {
    font-size: 14px;
}

</style>
<main id="content">
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12 mt-5">
                <h6 class="font-weight-medium font-size-7 mb-4">{{ __('web.Subscription (Coming Soon)') }}</h6>
                @if(request()->payment=='success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Thank you!</strong> {{ request()->get('msg', 'your payment was successfull.') }}
                </div>
                @endif
                @if(request()->payment=='failed')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Sorry!</strong> {{ request()->get('msg', 'your payment didn\'t go through.') }}
                </div>
                @endif
            </div>
            <div class="col-md-6 pricing6">
                <div class="card card-shadow border-0 mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <h5 class="font-weight-medium mb-0 text-center">Basic Plan</h5>
                            <div class="ml-auto"><span class="font-weight-normal p-2">&nbsp;</span></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-center">
                                <div class="price-box my-3">
                                    <sup>৳</sup><span class="text-dark display-5">@money_nz($items['subscription_1'])</span>
                                    <h6 class="font-weight-light">MONTHLY</h6>
                                    <a class="btn btn-info-gradiant font-14 border-0 text-white p-3 btn-block mt-3" href="?month=1">CHOOSE PLAN </a>
                                </div>
                            </div>
                            <div class="col-lg-7 align-self-center">
                                <ul class="list-inline pl-3 font-14 font-weight-medium text-dark">
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Regular Ebook</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>School & College Books</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>University Books</span></li>
                                    <li class="py-2"><i class="fal fa-times-circle text-danger mr-2"></i> <span>Exclusive Collection</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Device Support</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pricing6">
                <div class="card card-shadow border-0 mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <h5 class="font-weight-medium mb-0 text-center">Basic Plan</h5>
                            <div class="ml-auto"><span class="font-weight-normal p-2">&nbsp;</span></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-center">
                                <div class="price-box my-3">
                                    <sup>৳</sup><span class="text-dark display-5">@money_nz($items['subscription_3'])</span>
                                    <h6 class="font-weight-light">3 MONTH</h6>
                                    <a class="btn btn-info-gradiant font-14 border-0 text-white p-3 btn-block mt-3" href="?month=3">CHOOSE PLAN </a>
                                </div>
                            </div>
                            <div class="col-lg-7 align-self-center">
                                <ul class="list-inline pl-3 font-14 font-weight-medium text-dark">
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Regular Ebook</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>School & College Books</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>University Books</span></li>
                                    <li class="py-2"><i class="fal fa-times-circle text-danger mr-2"></i> <span>Exclusive Collection</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Device Support</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pricing6">
                <div class="card card-shadow border-0 mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <h5 class="font-weight-medium mb-0 text-center">Basic Plan</h5>
                            <div class="ml-auto"><span class="badge badge-danger font-weight-normal p-2">Popular</span></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-center">
                                <div class="price-box my-3">
                                    <sup>৳</sup><span class="text-dark display-5">@money_nz($items['subscription_6'])</span>
                                    <h6 class="font-weight-light">6 MONTH</h6>
                                    <a class="btn btn-info-gradiant font-14 border-0 text-white p-3 btn-block mt-3" href="?month=6">CHOOSE PLAN </a>
                                </div>
                            </div>
                            <div class="col-lg-7 align-self-center">
                                <ul class="list-inline pl-3 font-14 font-weight-medium text-dark">
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Regular Ebook</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>School & College Books</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>University Books</span></li>
                                    <li class="py-2"><i class="fal fa-times-circle text-danger mr-2"></i> <span>Exclusive Collection</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Device Support</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pricing6">
                <div class="card card-shadow border-0 mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <h5 class="font-weight-medium mb-0 text-center">Basic Plan</h5>
                            <div class="ml-auto"><span class="font-weight-normal p-2">&nbsp;</span></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-center">
                                <div class="price-box my-3">
                                    <sup>৳</sup><span class="text-dark display-5">@money_nz($items['subscription_12'])</span>
                                    <h6 class="font-weight-light">1 YEAR</h6>
                                    <a class="btn btn-info-gradiant font-14 border-0 text-white p-3 btn-block mt-3" href="?month=12">CHOOSE PLAN </a>
                                </div>
                            </div>
                            <div class="col-lg-7 align-self-center">
                                <ul class="list-inline pl-3 font-14 font-weight-medium text-dark">
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Regular Ebook</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>School & College Books</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>University Books</span></li>
                                    <li class="py-2"><i class="fal fa-times-circle text-danger mr-2"></i> <span>Exclusive Collection</span></li>
                                    <li class="py-2"><i class="fal fa-check-circle text-info mr-2"></i> <span>All Device Support</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        {{-- <div class="row">
            <div class="col-md-9">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
                        <div class="pt-5 pt-lg-8 pl-md-5 pl-lg-9 space-bottom-2 space-bottom-lg-3 mb-xl-1">
                            
                            
                            
                            <div class="space-bottom-1 space-bottom-lg-3 pricing-table">
                                <div class="row no-gutters-xl pb-lg-3">
                                    <div class="col-md-6 col-xl-4">
                                        <div class="border border-sh-hover transition-3d-hover space-top-2 px-3 px-wd-7 pb-6 text-center mb-5 mb-xl-0">
                                            <div class="mb-5 mb-lg-10 pt-lg-3 pb-lg-1">
                                                <h6 class="font-weight-medium font-size-7 mb-3">1 Month</h6>
                                                <div class="d-flex flex-column">
                                                    <div class="mb-3">
                                                        <sup class="font-size-2">৳</sup>
                                                        <sub class="font-size-7">@money_nz($items['subscription_1'])</sub>
                                                    </div>
                                                    <span class="font-size-2 text-gray-600">per month</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-6 pb-1">
                                                <li class=" mb-2 pb-1">Full Access to all Eboks</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Customer Experience</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Reading Experience</li>
                                                <li class=" mb-2 pb-1">Windows, Mac, Linux Support</li>
                                                <li class=" mb-2 pb-1">Android and iOS Support</li>
                                                <li class="mb-0">24/7 phone and email support</li>
                                            </ul>
                                            <div class="d-flex justify-content-center">
                                                <a href="?month=1" type="button" class="btn btn-block btn-dark text-white rounded-0 transition-3d-hover height-60">{{ __('web.Get Started') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="border  border-sh-hover transition-3d-hover space-top-2 px-3 px-wd-7 pb-6 text-center mb-5 mb-xl-0">
                                            <div class="mb-5 mb-lg-10 pt-lg-3 pb-lg-1">
                                                <h6 class="font-weight-medium font-size-7 mb-3">6 Month</h6>
                                                <div class="d-flex flex-column">
                                                    <div class="mb-3">
                                                        <sup class="font-size-2">৳</sup>
                                                        <sub class="font-size-7">@money_nz($items['subscription_6'])</sub>
                                                    </div>
                                                    <span class="font-size-2 text-gray-600">pay once</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-6 pb-1">
                                                <li class=" mb-2 pb-1">Full Access to all Eboks</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Customer Experience</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Reading Experience</li>
                                                <li class=" mb-2 pb-1">Windows, Mac, Linux Support</li>
                                                <li class=" mb-2 pb-1">Android and iOS Support</li>
                                                <li class="mb-0">24/7 phone and email support</li>
                                            </ul>
                                            <div class="d-flex justify-content-center">
                                                <a href="?month=6" type="button" class="btn btn-block btn-dark text-white rounded-0 transition-3d-hover height-60">{{ __('web.Get Started') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="border   border-sh-hover transition-3d-hover space-top-2 px-3 px-wd-7 pb-6 text-center mb-5 mb-xl-0">
                                            <div class="mb-5 mb-lg-10 pt-lg-3 pb-lg-1">
                                                <h6 class="font-weight-medium font-size-7 mb-3">1 Year</h6>
                                                <div class="d-flex flex-column">
                                                    <div class="mb-3">
                                                        <sup class="font-size-2">৳</sup>
                                                        <sub class="font-size-7">@money_nz($items['subscription_12'])</sub>
                                                    </div>
                                                    <span class="font-size-2 text-gray-600">Per Month</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-6 pb-1">
                                                <li class=" mb-2 pb-1">Full Access to all Eboks</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Customer Experience</li>
                                                <li class=" mb-2 pb-1">Quality &amp; Reading Experience</li>
                                                <li class=" mb-2 pb-1">Windows, Mac, Linux Support</li>
                                                <li class=" mb-2 pb-1">Android and iOS Support</li>
                                                <li class="mb-0">24/7 phone and email support</li>
                                            </ul>
                                            <div class="d-flex justify-content-center">
                                                <a href="?month=12" type="button" class="btn btn-block btn-dark text-white rounded-0 transition-3d-hover height-60">{{ __('web.Get Started') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row no-gutters row-cols-1 row-cols-md-2 row-cols-lg-3">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</main>

<button type="button" id="sslczPayBtn" style="display: none;" token="{{ csrf_token() }}" postdata="" order="{{ request()->get('month',1) }}" endpoint="{{ url('my-account/subscription') }}" class="btn btn-block btn-dark text-white rounded-0 transition-3d-hover height-60">Buy Now</button>

@endsection

@push('scripts')

<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };
        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);

    function buySubscription(id){
        alert(id);
    }

    $(document).ready(function() {
        @if(request()->month)
        setTimeout(function(){
            $("#sslczPayBtn").click();
            $("#loader").hide();
        },1500)
        @endif        
    });
</script>
@endpush