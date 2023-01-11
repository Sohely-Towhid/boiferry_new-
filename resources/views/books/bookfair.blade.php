@extends('layouts.books')
@section('title','বইমেলা')
@php
$seo_title = "বইমেলা";
$meta_description = '"বইফেরী" অনলাইনে বই কেনা-বেচা এবং পড়ার একটি আদর্শ মার্কেটপ্লেস। বাংলাদেশে সর্বপ্রথম আমরাই দিচ্ছি অনলাইনে জেনুইন "ইবুক" পড়ার সুবিধা, "যত খুশি পড়ুন" স্টাইলে। এবার বই কিনুন এবং পড়ুন নিশ্চিন্তে।';
$keywords = "";
@endphp
@include('page-seo')
@section('content')
@php
// $setting = loadSetting();
@endphp

<div class="banner space-top-1">
    <div class="container">
        <div class="bg-dark pl-xl-10 px-6 py-5 pt-lg-8 pb-lg-7s rounded-md">
            <div class="media d-block d-lg-flex">
                <div class="media-body align-self-center mb-4 mb-lg-0">
                    <p class="banner__pretitle text-uppercase text-gray-400 font-weight-bold">{{ __('web.Bookfair') }}</p>
                    <h2 class="banner__title font-size-10 font-weight-bold text-white mb-4">{{ __('web.Amar Ekushey Book Fair') }} {{ $year }}</h2>
                    @if($year==e2b(date('Y')))
                    <h3 class="banner__title font-size-8 font-weight-bold text-white mb-4">{{ __('web.fair_discount', ['amount'=>25]) }}</h3>
                    @else
                    <h3 class="banner__title font-size-8 font-weight-bold text-white mb-4">{{ __('web.All the books published in the book fair', ['year'=>e2b($year)]) }}</h3>
                    @endif
                </div>
                <img src="{{ asset('assets/images/book-fair.png') }}" class="img-fluid" alt="image-description">
            </div>
        </div>
        <div class="bg-blue p-3 rounded-md mt-5 text-center">
            <a href="{{ url('books?year='.b2e($year, true)) }}" class="text-white m-0"><h2>{{ __('web.bf_all_books') }}</h2></a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3 mt-6">
        @include('books.sidebar')
        </div>

        <div class="col-md-9">
        @foreach($list as $key => $block_list)
            <section class="space-1">
                @php
                if(@$block_list['cats']){
                    // withCacheCooldownSeconds(60 * 3)
                    $block_list['items'] = App\Models\Book::
                    whereMonth('published_at', '02')->whereYear('published_at',$_year)
                    ->whereIn('category_id', $block_list['cats'])
                    ->where('status', 1)->take(8)->get();
                }
                @endphp
                @include('books.joined_single_line', ['_title'=> $block_list['title'], '_link'=> $block_list['link'], '_loop'=> $block_list['items'], '_show'=> 5, '_bg'=> '', '_section'=>'space-bootom-1'])
            </section>
        @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush