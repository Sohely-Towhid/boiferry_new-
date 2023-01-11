@push('seo')
    @php
    if(@$og==false){
        $og = Cache::remember('seo_slider', 5 * 30, function () {
            return App\Models\Slider::where('type','Book')->where('status',1)->orderBy('id','asc')->first();
        });
        $og = ($og) ? showImage($og->image) : asset('assets/images/banner.webp');
    }
    $og_time = date('Y-m-d H:i:s');
    @endphp
    <!-- Primary Meta Tags -->
    <meta name="title" content="বইফেরী — {{ $seo_title }}">
    <meta name='description' itemprop='description' content='{!! $meta_description !!}' />
    <meta name='keywords' content='{{ (is_array(@$keywords)) ?  @implode(", ", @$keywords) : '' }}' />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ canonical_url() }}">
    <meta property="og:title" content="বইফেরী — {{ $seo_title }}">
    <meta property="og:description" content="{{ $meta_description }}">
    <meta property="og:image" content="{{ $og }}">
    <meta property="og:image:url" content="{{ $og }}" />
    <meta property="og:image:alt" content="{{ asset('assets/images/banner.webp') }}" />
    <meta property="og:updated_time" content="{{ $og_time }}" />
    <meta property="og:locale" content="en-us" />
    <meta property="og:locale:alternate" content="bn-BD" />
    <meta property="og:site_name" content="বইফেরী" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ canonical_url() }}">
    <meta property="twitter:title" content="বইফেরী — {{ $seo_title }}">
    <meta property="twitter:description" content="{{ $meta_description }}">
    <meta property="twitter:image" content="{{ $og }}">
    <meta property="twitter:site" content="@boiferry" />
@endpush