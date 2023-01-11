@php
$_c_u = request()->path();
// Main Menu
$menu[] = ['title'=>'Dashboard', 'link'=>'/', 'role'=>['*'], 'icon'=>'fa fa-tachometer-alt', 'sub'=>false, 'regex'=>'\/'];

// Media

$_book[] = ['title'=>'New Book', 'link'=>'book/create', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_book[] = ['title'=>'In Review', 'link'=>'book?type=pending', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_book[] = ['title'=>'Active', 'link'=>'book?type=active', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_book[] = ['title'=>'Stock Out', 'link'=>'book?type=stockout', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_book[] = ['title'=>'Rejected', 'link'=>'book?type=stopped', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_book[] = ['title'=>'All Book', 'link'=>'book', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$menu[] = ['title'=>'Book', 'link'=>'#', 'role'=>['*'], 'icon'=>'fa fa-books', 'sub'=>$_book, 'regex'=>'book.*'];

// $_product[] = ['title'=>'New Product', 'link'=>'product/create', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $_product[] = ['title'=>'In Review', 'link'=>'product?type=pending', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $_product[] = ['title'=>'Active', 'link'=>'product?type=active', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $_product[] = ['title'=>'Out of Stock', 'link'=>'product?type=stockout', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $_product[] = ['title'=>'Rejected', 'link'=>'product?type=rejected', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $_product[] = ['title'=>'All Product', 'link'=>'product', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
// $menu[] = ['title'=>'Product', 'link'=>'#', 'role'=>['*'], 'icon'=>'fa fa-box', 'sub'=>$_product, 'regex'=>'product.*'];

$_invoice[] = ['title'=>'Pending', 'link'=>'invoice?status=pending', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_invoice[] = ['title'=>'Packed', 'link'=>'invoice?status=packed', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_invoice[] = ['title'=>'In Shipping', 'link'=>'invoice?status=shipping', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_invoice[] = ['title'=>'Cancelled', 'link'=>'invoice?status=cancelled', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_invoice[] = ['title'=>'Completed', 'link'=>'invoice?status=completed', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_invoice[] = ['title'=>'All Invoice', 'link'=>'invoice', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$menu[] = ['title'=>'Invoice', 'link'=>'#', 'role'=>['*'], 'icon'=>'fa fa-file-invoice', 'sub'=>$_invoice, 'regex'=>nav_active($_invoice)];

$_finance[] = ['title'=>'Account Statement', 'link'=>'finance/statement', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_finance[] = ['title'=>'Order Overview', 'link'=>'finance/overview', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_finance[] = ['title'=>'Transaction Overview', 'link'=>'finance/transaction', 'role'=>['*'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$menu[] = ['title'=>'Finance', 'link'=>'#', 'role'=>['*'], 'icon'=>'fas fa-analytics', 'sub'=>$_finance, 'regex'=>nav_active($_finance)];
// Backup
$menu[] = ['title'=>'Setting', 'link'=>'setting', 'role'=>['*'], 'icon'=>'fa fa-cogs', 'sub'=>false, 'regex'=>'setting*'];
@endphp


@foreach($menu as $menu_key => $menu_item)
@if($menu_item['sub'])
    @if(show_if($menu_item['role'],$user))
    <li class="menu-item menu-item-submenu{{ nav_active_show($menu_item['regex'],$_c_u) }}" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle" id="topnav-{{ $menu_key }}">
            <i class="{{ $menu_item['icon'] }} menu-icon"></i>
            <span class="menu-text">{{ $menu_item['title'] }}</span>
            <i class="menu-arrow fa fa-angle-down"></i>
        </a>
        <div class="menu-submenu">
            <ul class="menu-subnav">
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                    <span class="menu-link">
                        <span class="menu-text">{{ $menu_item['title'] }}</span>
                    </span>
                </li>
                @foreach($menu_item['sub'] as $sub_menu)
                    @if(show_if($sub_menu['role'],$user))
                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{ ($sub_menu['link']=='#')? "#" : url($sub_menu['link']) }}" class="menu-link">
                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                            <span class="menu-text">{{ $sub_menu['title'] }}</span>
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </li>
    @endif
@else
    @if(show_if($menu_item['role'],$user))
    <li class="menu-item{{ nav_active_show($menu_item['regex'],$_c_u) }}">
        <a href="{{ ($menu_item['link']=='#')? "#" : url($menu_item['link']) }}" class="menu-link">
            <i class="menu-icon {{ $menu_item['icon'] }}"></i>
            <span class="menu-text">{{ $menu_item['title'] }}</span>
        </a>
    </li>
    @endif
@endif
@endforeach
