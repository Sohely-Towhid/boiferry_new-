@extends('layouts.books')
@section('title',__('web.My Account'))
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
                            <h6 class="font-weight-medium font-size-7 ml-lg-1 mb-lg-8 pb-xl-1">{{ __('web.Support') }}</h6>
                            
                            <div class="row">

                                <div class="col-lg-12 mx-auto">
                                    <div class="bg-white mt-n10 mt-md-n13 pt-5 pt-lg-7 px-3 px-md-5 pl-xl-10 pr-xl-3">
                                        <div class="ml-xl-4">
                                            <div class="mb-4 mb-lg-7">
                                                <h6 class="font-weight medium font-size-10 mb-4 mb-lg-7">{{ __('web.Contact Information') }}</h6>
                                                <p class="font-weight-medium font-italic">{{ __('web.help_details') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 mb-lg-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="font-weight-medium font-size-4 mb-4">{{ __('web.Dhaka Office') }}</h6>
                                                        <address class="font-size-2 mb-5">
                                                            <span class="mb-2 font-weight-normal text-dark">
                                                                {!! __('web.full_address') !!}
                                                            </span>
                                                        </address>
                                                        <div>
                                                            <a href="mailto:sale@boiferry.com" class="font-size-2 d-block link-black-100 mb-1">sales@boiferry.com</a>
                                                            <a href="tel:09638112112" class="font-size-2 d-block link-black-100">09638112112</a>
                                                            {{-- <a href="tel:01127235857" class="font-size-2 d-block link-black-100">01127235857</a> --}}
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="mb-5 mb-xl-9 pb-xl-1">
                                                <h6 class="font-size-4 font-weight-medium">{{ __('web.Social Media') }}</h6>
                                                <ul class="list-unstyled mb-0 d-flex">
                                                    <li class="btn pl-0">
                                                        <a class="link-black-100" href="#">
                                                            <span class="fab fa-instagram"></span>
                                                        </a>
                                                    </li>
                                                    <li class="btn">
                                                        <a class="link-black-100" href="#">
                                                            <span class="fab fa-facebook-f"></span>
                                                        </a>
                                                    </li>
                                                    <li class="btn">
                                                        <a class="link-black-100" href="#">
                                                            <span class="fab fa-youtube"></span>
                                                        </a>
                                                    </li>
                                                    <li class="btn">
                                                        <a class="link-black-100" href="#">
                                                            <span class="fab fa-twitter"></span>
                                                        </a>
                                                    </li>
                                                    <li class="btn">
                                                        <a class="link-black-100" href="#">
                                                            <span class="fab fa-pinterest"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            {{-- <div>
                                                <h6 class="font-weight-medium font-size-10 mb-3 pb-xl-1">Get In Touch</h6>
                                                <form class="js-validate" novalidate="novalidate">
                                                    <div class="row">

                                                        <div class="col-sm-6 mb-5">
                                                            <div class="js-form-message">
                                                                <label for="exampleFormControlInput1">Name</label>
                                                                <input id="exampleFormControlInput1" type="text" class="form-control rounded-0" name="name" aria-label="Jack Wayley" required="" data-error-class="u-has-error" data-msg="Please enter your name." data-success-class="u-has-success">
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-6 mb-5">
                                                            <div class="js-form-message">
                                                                <label for="exampleFormControlInput2">Email</label>
                                                                <input id="exampleFormControlInput2" type="email" class="form-control rounded-0" name="email" aria-label="jackwayley@gmail.com" required="" data-msg="Please enter a valid email address." data-error-class="u-has-error" data-success-class="u-has-success">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 mb-5">
                                                            <div class="js-form-message">
                                                                <label for="exampleFormControlInput3">Subject</label>
                                                                <input id="exampleFormControlInput3" type="email" class="form-control rounded-0" name="email" aria-label="jackwayley@gmail.com" required="" data-msg="Please enter a valid email address." data-error-class="u-has-error" data-success-class="u-has-success">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 mb-5">
                                                            <div class="js-form-message">
                                                                <div class="input-group flex-column">
                                                                    <label for="exampleFormControlInput4">Details please! Your review helps other shoppers.</label>
                                                                    <textarea id="exampleFormControlInput4" class="form-control rounded-0 pl-3 font-size-2 placeholder-color-3" rows="6" cols="77" name="text" placeholder="What did you like or dislike? What should other shoppers know before buying?" aria-label="Hi there, I would like to ..." required="" data-msg="Please enter a reason." data-error-class="u-has-error" data-success-class="u-has-success"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col d-flex justify-content-lg-start">
                                                            <button type="submit" class="btn btn-wide btn-dark text-white rounded-0 transition-3d-hover height-60">Sumbit Message</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div> --}}
                                        </div>
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