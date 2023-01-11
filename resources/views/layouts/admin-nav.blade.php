@php
// Main Menu
$menu[] = ['title'=>'Dashboard', 'link'=>'/', 'role'=>['*'], 'icon'=>'dripicons-meter', 'sub'=>false, 'regex'=>'\/'];

// Submenu
$_sales[] = ['title'=>'New Sale', 'link'=>'sales', 'role'=>['admin','sales','accounts'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_sales[] = ['title'=>'Customers', 'link'=>'customer', 'role'=>['admin','sales','accounts'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_sales[] = ['title'=>'Invoices', 'link'=>'invoice', 'role'=>['admin','sales','accounts'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_sales[] = ['title'=>'Requiring Invoices', 'link'=>'invoice?type=requiring', 'role'=>['admin','sales','accounts'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$_sales[] = ['title'=>'Refunds', 'link'=>'refund', 'role'=>['admin','sales','accounts'], 'icon'=>'', 'sub'=>false, 'regex'=>false];
$menu[] = ['title'=>'Sales', 'link'=>'#', 'role'=>['admin','sales','accounts'], 'icon'=>'dripicons-basket', 'sub'=>$_sales, 'regex'=>nav_active($_sales)];
@endphp
