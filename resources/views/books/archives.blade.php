@extends('layouts.books')
@section('title',$title)
@section('content')
@php
$color = ['indigo-light','tangerine-light','chili-light','carolina-light','punch-light'];
$locale = (App::currentLocale()=='en') ? '' : '_bn';
@endphp
<style>
    .product-category__body{
        height: 3.37rem;
    }
    .product-category__body h3{
        height: auto;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
<div class="container">
    <div class="page-header border-bottom">
        <div class="container">
            <div class="d-md-flex justify-content-between align-items-center py-4">
                <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">{{ $title }}</h1>
                <nav class="woocommerce-breadcrumb font-size-2">
                    <a href="{{ url('/') }}" class="h-primary">{{ __('web.Home') }}</a>
                    <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                    @if(isset($authors))
                    <a href="#" class="h-primary">{{ __('web.Authors') }}</a>
                    @endif
                    @if(isset($categories))
                    <a href="#" class="h-primary">{{ __('web.Subjects') }}</a>
                    @endif
                    @if(isset($publishers))
                    <a href="#" class="h-primary">{{ __('web.Publication') }}</a>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    <section class="space-bottom-2 space-bottom-lg-3 space-top-1">
        <div class="row">
            @if(isset($authors))
            <form action="" method="GET" class="col-md-12">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" name="search" placeholder="Author Name..." value="{{ request()->search }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary" type="button">Search</button>
                    </div>
                </div>
            </form>
            @foreach($items as $item)
            <div class="col-6 col-md-2">
                <a href="{{ url('author',$item->slug) }}">
                    <img class="rounded-circle img-fluid mb-3 author" data-src="{{ ($item->photo) ? showImage($item->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" alt="Image Description">
                    <div class="py-3 text-center">
                        <h4 class="h6 text-dark">{{ $item->name_bn }}</h4>
                        <span class="font-size-2 text-secondary-gray-700">{{ $item->name }}</span>
                    </div>
                </a>
            </div>
            @endforeach
            @endif

            @if(isset($categories))
            <form action="" method="GET" class="col-md-12">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" name="search" placeholder="Subject Name..." value="{{ request()->search }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary" type="button">Search</button>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <ul class="list-unstyled my-0 row row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-wd-5">
                    @foreach($items as $item)
                    <li class="product-category col mb-4 mb-xl-5">
                        <a href="{{ url('category',$item->slug) }}" class="stretched-link text-dark">
                        <div class="product-category__inner bg-{{ $color[array_rand($color)]}} px-6 py-5">
                            <div class="product-category__body text-center">
                                <h3 class="font-size-3 text-height-3 crop-text-3">{{ $item->{'name'.$locale} }}</h3>
                            </div>
                        </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(isset($publishers))
            <form action="" method="GET" class="col-md-12">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" name="search" placeholder="Publishers Name..." value="{{ request()->search }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary" type="button">Search</button>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <ul class="list-unstyled my-0 row row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-wd-5">
                    @foreach($items as $item)
                    <li class="product-category col mb-4 mb-xl-5">
                        <a href="{{ url('publisher',$item->slug) }}" class="stretched-link text-dark">
                        <div class="product-category__inner bg-{{ $color[array_rand($color)]}} px-6 py-5">
                            <div class="product-category__body text-center">
                                <h3 class="font-size-3 text-height-3 crop-text-3">{{ $item->{'name'.$locale} }}</h3>
                            </div>
                        </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="col-md-12 text-center">
                @if($items->total()==0)
                <img src="{{ asset('assets/images/no-books.svg') }}" alt="" width="650px;">
                @endif
                {!! $items->withQueryString()->links() !!}
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $('ul.pagination').addClass('justify-content-center');
    $('.author').Lazy({
        beforeLoad: function(element) {
            element.attr('src', '{{ asset('assets/images/default-profile.jpg') }}');
        },
        onError: function(element) {
            element.attr('src', '{{ asset('assets/images/default-profile.jpg') }}');
        },
    });
</script>
@endpush