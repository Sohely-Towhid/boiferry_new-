@php
$locale = (App::currentLocale()=='en') ? '' : '_bn';
$menu[] = ['title'=>__('web.Home'), 'link'=>'/', 'auth'=>false, 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'\/'];

// Submenu
$authors = cache('book_authors'.$locale);
if(!$authors){
    $authors = App\Models\Author::take(49)->get(['name'.$locale.' as title','slug as link', 'id as append'])->toArray();
    Cache::add('book_authors'.$locale, $authors, now()->addMinutes(60));
}
$authors[] = ['title'=>__('web.See more...'), 'link'=> 'authors', 'append'=> false];
$menu[] = ['title'=>__('web.Authors'), 'link'=>'/authors', 'auth'=> false, 'sub'=>$authors, 'regex'=>'authors|author.*', 'append'=>'author'];

$category = cache('book_category'.$locale);
if(!$category){
    $category = App\Models\Category::take(49)->get(['name'.$locale.' as title','slug as link', 'id as append'])->toArray();
    Cache::add('book_category'.$locale, $category, now()->addMinutes(60));
}

$category[] = ['title'=>__('web.See more...'), 'link'=> 'category', 'append'=> false];
$menu[] = ['title'=>__('web.Subjects'), 'link'=>'/category', 'auth'=> false, 'sub'=>$category, 'regex'=>'category|publisher.*', 'append'=>'category'];


$publication = cache('book_publishers'.$locale);
if(!$publication){
    $publication = App\Models\Publication::take(49)->get(['name'.$locale.' as title','slug as link', 'id as append'])->toArray();
    Cache::add('book_publishers'.$locale, $publication, now()->addMinutes(60));
}
$publication[] = ['title'=>__('web.See more...'), 'link'=> 'publication', 'append'=> false];
$menu[] = ['title'=>__('web.Publications'), 'link'=>'/publisher', 'auth'=> false, 'sub'=>$publication, 'regex'=>'publisher|publisher.*', 'append'=>'publisher'];

foreach (range(date('Y'), date('Y') - 9) as $key => $value) {
    $fair[] = ['title'=>__('web.Bookfair').' '.e2b($value),'link'=> $value ,'append'=>'boimela'];
}
$menu[] = ['title'=>__('web.Bookfair'), 'link'=>'/boimela', 'auth'=> false, 'sub'=>$fair, 'regex'=>'boimela|publisher.*', 'append'=>'boimela'];


$menu[] = ['title'=>__('web.Bestseller Book'), 'link'=>'/bestseller', 'auth'=>false, 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'bestseller.*'];
$menu[] = ['title'=>__('web.Fiction'), 'link'=>'category/novel-books', 'auth'=>false, 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'fiction.*'];
$menu[] = ['title'=>__('web.Pre Order'), 'link'=>'pre-order', 'auth'=>false, 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'category.*pre\-order'];
// $menu[] = ['title'=>__('web.Freelance/ Programming'), 'link'=>'category/computer-internet-freelancing-and-outsourcing', 'auth'=>false, 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'category.*programming'];
@endphp


@push('top_menu')
<ul class="nav">
    @foreach($menu as $key => $_menu)
    @if($_menu['sub'])
    @php $is_mega = (count($_menu['sub'])>10) ? 1 : 0; @endphp
    <li class="nav-item {{ ($is_mega) ? '' : 'dropdown' }}">
        @if(count($_menu['sub'])>10)
        <a id="nav_{{$key}}Invoker" href="#" class="dropdown-toggle nav-link link-black-100 mx-3 px-0 py-3 font-size-2 font-weight-medium d-flex align-items-center" aria-haspopup="true" aria-expanded="false" data-unfold-event="hover" data-unfold-target="#nav_{{$key}}Menu" data-unfold-type="css-animation" data-unfold-duration="200" data-unfold-delay="50" data-unfold-hide-on-scroll="true" data-unfold-animation-in="slideInUp" data-unfold-animation-out="fadeOut">
            {{ $_menu['title'] }}
        </a>
        <div id="nav_{{$key}}Menu" class="p-0 dropdown-unfold dropdown-menu megamenu font-size-2 rounded-0 border-gray-900" aria-labelledby="nav_{{$key}}Invoker" style="width:1100px;">
            <div class="row no-gutters">
                <div class="col-12 p-3">
                    <div class="row">
                    @foreach(array_chunk($_menu['sub'], 10) as $_sub_ch)
                    <div class="col">
                        <ul class="menu list-unstyled">
                            @foreach($_sub_ch as $_sub_ch_)
                            <li><a href="{{ url((@$_sub_ch_['append']) ? @$_menu['append'].'/'.$_sub_ch_['link'] : $_sub_ch_['link']) }}" class="d-block link-black-100 py-1">{{ $_sub_ch_['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <a id="nav_{{$key}}Invoker" href="#" class="dropdown-toggle nav-link link-black-100 mx-3 px-0 py-3 font-size-2 font-weight-medium" aria-haspopup="true" aria-expanded="false" data-unfold-event="hover" data-unfold-target="#nav_{{$key}}Menu" data-unfold-type="css-animation" data-unfold-duration="200" data-unfold-delay="50" data-unfold-hide-on-scroll="true" data-unfold-animation-in="slideInUp" data-unfold-animation-out="fadeOut">
            {{ $_menu['title'] }}
        </a>
        <ul id="nav_{{$key}}Menu" class="dropdown-unfold dropdown-menu font-size-2 rounded-0 border-gray-900" aria-labelledby="nav_{{$key}}Invoker">
            @foreach($_menu['sub'] as $s_m)
            <li><a href="{{ url((@$s_m['append']) ? @$_menu['append'].'/'.$s_m['link'] : $s_m['link']) }}" class="dropdown-item link-black-100">{{ $s_m['title'] }}</a></li>
            @endforeach
        </ul>
        @endif
    </li>
    @else
    <li class="nav-item">
        <a href="{{ url($_menu['link']) }}" class="nav-link link-black-100 mx-3 px-0 py-3 font-size-2 font-weight-medium" >
            {{ $_menu['title'] }}
        </a>
    </li>
    @endif
    @endforeach
</ul>
@endpush

@push('mobile_menu')
<ul>
    @foreach($menu as $key => $_menu)
    @if($_menu['sub'])
    @php $is_mega = (count($_menu['sub'])>10) ? 1 : 0; @endphp
    <li>
        <a href="#" id="{{ $key }}">{{ $_menu['title'] }} <span class="fas fa-caret-down"></span> </a>
            <ul class="item-show-{{ $key }}">
                @foreach($_menu['sub'] as $s_m)
                <li><a href="{{ url((@$s_m['append']) ? @$_menu['append'].'/'.$s_m['link'] : $s_m['link']) }}">{{ $s_m['title'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </li>
    @else
    <li>
        <a href="{{ url($_menu['link']) }}" >
            {{ $_menu['title'] }}
        </a>
    </li>
    @endif
    @endforeach
    <li>
        <a href="{{ url('/cart') }}" >
            {{ __('web.View Cart') }}
        </a>
    </li>
    <li>
        <a href="{{ url('/cart') }}" >
            {{ __('web.Checkout') }}
        </a>
    </li>
</ul>
@endpush
{{-- <ul>
    @foreach($menu as $key => $_menu)
    @if($_menu['sub'])
    @php $is_mega = (count($_menu['sub'])>10) ? 1 : 0; @endphp
    <li class="has-submenu">
        <a href="#" data-submenu="menu_{{ $key }}">{{ $_menu['title'] }}</a>
        <div id="menu_{{ $key }}" class="submenu">
             <div class="submenu-header" data-submenu-close="menu_{{ $key }}">
                <a href="#">{{ $_menu['title'] }}</a>
            </div>
            <ul>
                @foreach($_menu['sub'] as $s_m)
                <li><a href="{{ url((@$s_m['append']) ? @$_menu['append'].'/'.$s_m['link'] : $s_m['link']) }}">{{ $s_m['title'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </li>
    @else
    <li class="nav-item">
        <a href="{{ url($_menu['link']) }}" class="nav-link link-black-100 mx-3 px-0 py-3 font-size-2 font-weight-medium" >
            {{ $_menu['title'] }}
        </a>
    </li>
    @endif
    @endforeach
</ul> --}}