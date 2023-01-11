@extends('layouts.books')
@section('title',__('web.Home'))
@php
$seo_title = "নীড়";
$meta_description = '"বইফেরী" অনলাইনে বই কেনা-বেচা এবং পড়ার একটি আদর্শ মার্কেটপ্লেস। বাংলাদেশে সর্বপ্রথম আমরাই দিচ্ছি অনলাইনে জেনুইন "ইবুক" পড়ার সুবিধা, "যত খুশি পড়ুন" স্টাইলে। এবার বই কিনুন এবং পড়ুন নিশ্চিন্তে।';
$keywords = "";
@endphp
@include('page-seo')
@section('content')
@php
$locale = (App::currentLocale()=='en') ? '' : '_bn';
$setting = loadSetting();
$feature = loadSetting('feature');
@endphp
<section class="space-bottom-1">
    <div class="bg-gray-200 space-0 space-lg-0 bg-img-hero" style="background-image: url({{ asset('assets/images/bg-3.jpg') }});">
        <div class="container">
            <div class="js-slick-carousel u-slick" data-infinite="true" data-autoplay="true" data-pagi-classes="text-center u-slick__pagination position-absolute right-0 left-0 mb-n8 mb-lg-4 bottom-0" data-responsive='[{"breakpoint": 768,"settings": {"slidesToShow": 1}}, {"breakpoint": 554,"settings": {"slidesToShow": 1}}]'>
                @foreach($slides as $slide)
                <div class="js-slide">
                    <div class="hero row min-height-380 align-items-center">
                        @if($slide->text->line_1)
                        <div class="col-lg-7 col-wd-6 mb-4 mb-lg-0">
                            <div class="media-body mr-wd-4 align-self-center mb-4 mb-md-0">
                                <p class="hero__pretitle text-uppercase font-weight-bold text-gray-400 mb-2" data-scs-animation-in="fadeInUp" data-scs-animation-delay="200">{{ $slide->text->line_1 }}</p>
                                <h2 class="hero__title font-size-14 mb-4" data-scs-animation-in="fadeInUp" data-scs-animation-delay="300">
                                    <span class="hero__title-line-1 font-weight-regular d-block">{{ $slide->text->line_2 }}</span>
                                    <span class="hero__title-line-2 font-weight-bold d-block">{{ $slide->text->line_3 }}</span>
                                </h2>
                                <a href="{{ $slide->text->link }}" class="btn btn-dark btn-wide rounded-0 hero__btn" data-scs-animation-in="fadeInLeft" data-scs-animation-delay="400">{{ $slide->text->link_text }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-wd-6" data-scs-animation-in="fadeInRight" data-scs-animation-delay="500">
                            <img class="img-fluid" src="{{ showImage($slide->image) }}" alt="Slide">
                        </div>
                        @else
                        <div class="col-md-12">
                            <a href="{{ $slide->text->link }}">
                                <img class="img-fluid" src="{{ showImage($slide->image) }}" alt="Slide" width="100%">
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container d-none">
        <div class="row">
            @foreach($feature as $f)
            @if($f->status==1)
            <div class="col-lg-4">
                <div class="img-fluid height-200 mb-5 mb-lg-0 text-center" style="background-color: {{ ($f->value->bg) ? $f->value->bg: "#ececec" }};">
                    <a href="{{ ($f->value->link) ? url($f->value->link) : '' }}">
                        <img class="fit-image" src="{{ showImage($f->value->image) }}" alt="Feature">
                    </a>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>

<section class="space-bottom-1">
    <div class="space-1">
        @include('books.joined_single_line', ['_title'=> __('web.Latest selling books'), '_link'=>'', '_loop'=> $latest_sold, '_show'=> 8, '_bg'=> ''])
    </div>
</section>

<section class="space">
    <div class="space-1 bg-gray-200">
        @include('books.joined_single_line', ['_title'=> __('web.Bestseller of', ['year'=> e2b(date('Y'))]), '_link'=>url('bestseller?year='.date('Y')), '_loop'=> $best_seller_year, '_show'=> 8, '_bg'=> ''])
    </div>
</section>

@foreach($setting as $key => $block)
    @if(preg_match("/book_home_block_/", $block->name) && $block->status==1)
    <section class="space-bottom-1">
        <div class="space-1 {{ $block->value->bg }}">
            @php
            // withCacheCooldownSeconds(60 * 3)
            // dd($block->value->author);
            $__loops = App\Models\Book::where('status', 1) 
            ->where(function($query) use ($block) {
                if(is_array($block->value->category)){
                    $query = $query->whereIn('category_id', $block->value->category);
                }
                if(is_array($block->value->category) && is_array($block->value->author)){
                    $query = $query->orWhereIn('author_id', $block->value->author);
                }elseif(is_array($block->value->author)){
                    $query = $query->WhereIn('author_id', $block->value->author);
                }
            })
            ->orderBy('published_at','desc')->take($block->value->take_items)->get();
            if(@$block->value->opt_category){
                $__cats = App\Models\Category::withCacheCooldownSeconds(60 * 3)->whereIn('id', $block->value->opt_category)->get();
            }else{
                $__cats = false;
            }
            $_title = explode("|",$block->value->title);
            if($locale=='en'){ $_title = $_title[0]; }
            else{ $_title = (count($_title)>1) ? $_title[1] : $_title[0]; }
            @endphp
            {{-- value->category --}}
            @include('books.'.$block->value->theme, ['_title'=> $_title, '_link'=>url('category/cg?id='.$block->id), '_loop'=> $__loops, '_show'=> $block->value->show_items, '_bg'=> $block->value->bg])
        </div>
        @if($__cats)
        <div class="container space-1">
            <div class="d-flex justify-content-between align-items-center row row-cols-1 row-cols-md-5">
                @foreach($__cats as $cat)
                <div class="col mb-sm-2"><a href="{{ url('category', $cat->slug) }}" class="btn btn-block btn-cat text-center">{{ $cat->{'name'.$locale} }}</a></div>
                @endforeach
            </div>
        </div>
        @endif
    </section>    
    @endif
@endforeach

<section class="space-bottom-1 space-top-1">
    <div class="container">
        <header class="d-md-flex justify-content-between align-items-center mb-8">
            <h2 class="font-size-7 mb-3 mb-md-0">{{ __('web.Best seller authors of this week') }}</h2>
        </header>
        <ul class="row rows-cols-5 no-gutters authors list-unstyled js-slick-carousel u-slick" data-infinite="true" data-slides-show="7" data-arrows-classes="u-slick__arrow u-slick__arrow-centered--y" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-inner u-slick__arrow-inner--left ml-lg-n10" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-inner u-slick__arrow-inner--right mr-lg-n10" data-responsive='[{"breakpoint": 1025,"settings": {"slidesToShow": 3}}, {"breakpoint": 992,"settings": {"slidesToShow": 3}}, {"breakpoint": 768,"settings": {"slidesToShow": 3}}, {"breakpoint": 554,"settings": {"slidesToShow": 3}}]'>
            @foreach(App\Models\Author::take(10)->get() as $author)
            <li class="author col">
                <a href="{{ url('author', $author->slug) }}" class="text-reset">
                    <img src="{{ ($author->photo) ? showImage($author->photo, 'sm') : asset('assets/images/default-profile.jpg') }}" class="mx-auto mb-5 d-block rounded-circle w-100" alt="image-description">
                    <div class="author__body text-center">
                        <h2 class="author__name h6 mb-0">{{ $author->name_bn }}</h2>
                        <div class="text-gray-700 font-size-2">{{ $author->name }}</div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</section>
@endsection

@push('scripts')
@endpush