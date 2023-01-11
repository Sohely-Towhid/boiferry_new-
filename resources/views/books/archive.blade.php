@extends('layouts.books')
@section('title',$title)
@php
$locale = (App::currentLocale()=='en') ? '' : '_bn'; 
$seo_title = "আর্কাইভ";
$meta_description = '"বইফেরী" অনলাইনে বই কেনা-বেচা এবং পড়ার একটি আদর্শ মার্কেটপ্লেস। বাংলাদেশে সর্বপ্রথম আমরাই দিচ্ছি অনলাইনে জেনুইন "ইবুক" পড়ার সুবিধা, "যত খুশি পড়ুন" স্টাইলে। এবার বই কিনুন এবং পড়ুন নিশ্চিন্তে।';
$keywords = "";
$_og_text = '';
$_og_text_1 = '';
$_og_img = '';
if(isset($author)){$og = url('fb-feed?author='.$author->id); $_og_text = $author->{'name'.$locale}; }
if(isset($category)){$og = url('fb-feed?category='.$category->id); $_og_text = $category->{'name'.$locale}; $_og_text_1 = $items->total(); $_og_img = $category->photo; }
if(isset($publisher)){$og = url('fb-feed?publisher='.$publisher->id); $_og_text = $publisher->{'name'.$locale}; $_og_text_1 = $items->total(); $_og_img = $publisher->photo; }
@endphp
@include('page-seo')
@section('content')
<div class="container">
    <div class="page-header border-bottom">
        <div class="container">
            <div class="d-md-flex justify-content-between align-items-center py-4">
                <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">{{ $title }}</h1>
                <nav class="woocommerce-breadcrumb font-size-2">
                    <a href="{{ url('/') }}" class="h-primary">{{ __('web.Home') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    @if(isset($author))
                    <a href="#" class="h-primary">{{ __('web.Author') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    <span>{{ $author->{'name'.$locale} }}</span>
                    @endif
                    @if(isset($category))
                    <a href="#" class="h-primary">{{ __('web.Subject') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    <span>{{ $category->{'name'.$locale} }}</span>
                    @endif
                    @if(isset($other))
                    <a href="#" class="h-primary">{{ __('web.Archive') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    <span>{{ $title }}</span>
                    @endif
                    @if(isset($publisher))
                    <a href="#" class="h-primary">{{ __('web.Publication') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    <span>{{ $publisher->{'name'.$locale} }}</span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @if(isset($category) || isset($publisher))
    <div class="bg-dark pl-xl-10 px-6 py-5 pt-lg-8 pb-lg-7s rounded-md">
        <div class="media d-block d-lg-flex">
            <div class="media-body align-self-center mb-4 mb-lg-0">
                <h2 class="banner__title font-size-10 font-weight-bold text-white mb-4">{{ $_og_text }}</h2>
                @if($locale=='_bn')
                <h3 class="banner__title font-size-8 font-weight-bold text-white mb-4">{{ $_og_text }} প্রকাশিত মোট {{ e2b($_og_text_1) }} টি বই পাচ্ছেন বইফেরীতে...</h3>
                @else
                <h3 class="banner__title font-size-8 font-weight-bold text-white mb-4">A total of {{ $_og_text_1 }} books published by {{ $_og_text }} are available at Boiferi...</h3>
                @endif
            </div>
            <div class="bg-white">
                @if(isset($category))
                <img src="{{ ($category->photo) ? showImage($category->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" class="img-fluid arch" alt="image-description" style="width: 150px; height: 150px;">
                @elseif(isset($category))
                <img src="{{ ($publisher->photo) ? showImage($publisher->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" class="img-fluid arch" alt="image-description" style="width: 150px; height: 150px;">
                @endif
            </div>
        </div>
    </div>
    @endif
    <section class="space-bottom-2 space-bottom-lg-3 archive">
        <div class="row">
            @if(isset($author))
            <div class="col-md-12 mt-5"></div>
            <div class="col-md-3">
                <img class="img-fluid" src="{{ ($author->photo) ? showImage($author->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" alt="{{ $author->{'name'.$locale} }}">
            </div>
            <div class="col-md-9">
                <span class="text-gray-400 font-size-2">{{ __('web.AUTHOR BIOGRAPHY') }}</span>
                <h6 class="font-size-7 ont-weight-medium mt-2 mb-3 pb-1">{{ $author->name_bn }} ({{ $author->name }})</h6>
                <p class="mb-0">{{ $author->bio }}</p>
            </div>
            <div class="col-md-12 mt-5">
                <div class="mb-5">
                    @include('books.joined_multi_line', ['_title'=> __('web.Books By', ['author'=>$author->{'name'.$locale}]), '_link'=>false, '_loop'=> $items, '_show'=> 6, '_bg'=> '', '_section'=>'space-bootom-1'])
                </div>
                <div class="justify-content-center text-center">
                    @if($items->total()==0)
                    <img src="{{ asset('assets/images/no-books.svg') }}" alt="" style="max-width: 650px;">
                    @endif
                    {!! $items->withQueryString()->links() !!}
                </div>
            </div> 
            @endif

            @if(isset($category))
            <div class="col-md-12 mt-5">
                <div class="mb-5">
                    @include('books.joined_multi_line', ['_title'=> $category->{'name'.$locale}, '_link'=>false, '_loop'=> $items, '_show'=> 6, '_bg'=> '', '_section'=>'space-bootom-1'])
                </div>
                <div class="justify-content-center text-center">
                    @if($items->total()==0)
                    <img src="{{ asset('assets/images/no-books.svg') }}" alt="" style="max-width: 650px;">
                    @endif
                    {!! $items->withQueryString()->links() !!}
                </div>
            </div> 
            @endif

            @if(isset($publisher))
            <div class="col-md-12 mt-5">
                <div class="mb-5">
                    @include('books.joined_multi_line', ['_title'=> $publisher->{'name'.$locale}, '_link'=>false, '_loop'=> $items, '_show'=> 6, '_bg'=> '', '_section'=>'space-bootom-1'])
                </div>
                <div class="justify-content-center text-center">
                    @if($items->total()==0)
                    <img src="{{ asset('assets/images/no-books.svg') }}" alt="" style="max-width: 650px;">
                    @endif
                    {!! $items->withQueryString()->links() !!}
                </div>
            </div> 
            @endif

            @if(isset($other))
            <div class="col-md-3 mt-5">
                @include('books.sidebar')
            </div>
            <div class="col-md-9 mt-5">
                <div class="mb-5">
                    @include('books.joined_multi_line', ['_title'=> false, '_link'=>false, '_loop'=> $items, '_show'=> 5, '_bg'=> '', '_section'=>'space-bootom-1'])
                </div>
                <div class="justify-content-center text-center">
                    @if($items->total()==0)
                    <img src="{{ asset('assets/images/no-books.svg') }}" alt="" style="max-width: 650px;">
                    @endif
                    {!! $items->withQueryString()->links() !!}
                </div>
            </div> 
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $('ul.pagination').addClass('justify-content-center');
    $('.arch').Lazy({
        beforeLoad: function(element) {
            element.attr('src', window.base_url + '/assets/images/def-pub.jpg');
        },
        onError: function(element) {
            element.attr('src',  window.base_url + '/assets/images/def-pub.jpg');
        },
    });
</script>
@endpush