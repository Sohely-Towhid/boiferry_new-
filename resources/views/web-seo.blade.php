@push('seo')
@php 
$seo_type = 'website';
if(get_class($item)=='App\Models\Book'){$seo_type = 'website';}
if(get_class($item)=='App\Models\Product'){$seo_type = 'product';}
$og_image = str_replace(".jpg", '.webp', @$item->seo->og_image);
$og_image =  url('fb-feed?img=' . str_replace(['lg_','md_','sm_','xs_'],'',$og_image));
@endphp

    <!-- Primary Meta Tags -->
    <meta name="title" content="বইফেরী — {{ @$item->seo_title }}">
    <meta name='description' itemprop='description' content='{!! @$item->seo->meta_description !!}' />
    <meta name='keywords' content='{{ (is_array(@$item->seo->keywords)) ?  @implode(", ", $item->seo->keywords) : '' }}' />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $seo_type }}">
    <meta property="og:url" content="{{ canonical_url() }}">
    <meta property="og:title" content="বইফেরী — {{ @$item->seo_title }}">
    <meta property="og:description" content="{{ @$item->seo->meta_description }}">
    <meta property="og:image" content="{{ $og_image }}">
    <meta property="og:image:url" content="{{ $og_image }}" />
    <meta property="og:locale" content="en-us" />
    <meta property="og:locale:alternate" content="bn-BD" />
    <meta property="og:site_name" content="বইফেরী" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ canonical_url() }}">
    <meta property="twitter:title" content="বইফেরী — {{ @$item->seo_title }}">
    <meta property="twitter:description" content="{{ @$item->seo->meta_description }}">
    <meta property="twitter:image" content="{{ $og_image }}">
    <meta property="twitter:site" content="@boiferry" />


@if(@$item->seo->type=='product')
    <meta property="product:price:amount" content="{{ $item->sale }}"/>
    <meta property="product:price:currency" content="BDT"/>
@endif

@php

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Graph;
use App\Models\Review;
$graph = false;
if(get_class($item)=='App\Models\Book'){
    $graph = cache('book_json_ld_'.$item->id);
    if(!$graph){
        $seo_images = [];
        foreach ($item->images as $__s => $__seo) {
            $seo_images[] = showImage($__seo);
        }
        $graph = new Graph();
        $reviews = [];
        foreach (Review::with('user')->where('book_id', $item->id)->where('status',1)->orderBy('star','asc')->take(10)->get() as $review) {
            $reviews[] = Schema::review()->reviewRating(Schema::rating()->ratingValue($review->star)->bestRating(5)->reviewBody($review->message))->author(Schema::Person()->name($review->user->name));
        }
        $graph
            ->product()
            ->name(@$item->seo_title)
            ->description(@$item->seo->meta_description)
            ->price($item->rate)
            ->isbn($item->isbn)
            ->image($seo_images)
            ->sku(($item->sku)?$item->sku : 'boiferry_book_'.$item->id)
            ->offers(
                Schema::offer()
                    ->url(canonical_url())
                    ->availability(($item->stock) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock')
                    ->price($item->sale)
                    ->priceValidUntil(date('Y-m-d', strtotime('+7 days')))
                    ->itemCondition("https://schema.org/NewCondition")
                    ->priceCurrency('BDT')
            )->aggregateRating(
                Schema::aggregateRating()
                    ->ratingValue(@$item->rating_review->rating)
                    ->ratingCount(@$item->rating_review->rating_total))
            ->review($reviews)
            ->brand(Schema::brand()->name($item->seo_brand));
            $graph = json_encode($graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            cache(['book_json_ld_'.$item->id => $graph], now()->addMinutes(60));
    }
}
@endphp
@if($graph)
    <script type="application/ld+json">{!! $graph !!}</script>
@endif
@endpush