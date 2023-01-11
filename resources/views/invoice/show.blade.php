{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Invoice #'.$item->id)
@section('content')
@php
$auth = Auth::user();
$paid = $ssl = $bkash = $partial = $nagad = false;
$sn = json_encode($item->system_note);
$total = $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount;
if($item->partial_payment>0){
    $partial = true;
}
if(preg_match("/sslcommerz_card\#([0-9a-z_\|\s,\.]+)\"/i", $sn, $match)){
    $paid = true;
    if($item->partial_payment>0){
        $paid = false;
        $partial = true;
    }
    $ssl = $match[1];
}
if(preg_match("/bkash\#/i", $sn, $match)){
    $paid = true;
    $bkash = true;
    if($item->partial_payment>0){
        $paid = false;
        $partial = true;
    }
}
if(preg_match("/nagad\#/i", $sn, $match)){
    $paid = true;
    $nagad = true;
    if($item->partial_payment>0){
        $paid = false;
        $partial = true;
    }
}
if($item->partial_payment >= $total){
    $partial = false;
    $paid = true;
}
$status = ['-','Pending','Processing / Paid','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$emails = ['', '', 'Processing / Paid','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$pm = ['cod'=> 'CoD (100TK Advance)','cod-full'=>"COD (0 Advance)",'sslcommerz'=>'Sslcommerz (Full)','bkash'=>'bKash','nagad'=>'Nagad'];
$barcode = ean8(1000000+$item->id);

$winx = new App\Winx();
$winx_package = cache('winx_package');
if(!$winx_package){
    $winx_package = $winx->getPackage();
    cache(['winx_package' => $winx_package], now()->addMinutes(10));
}
$winx_locations = cache('winx_locations');
if(!$winx_locations){
    $winx_locations = $winx->getLocation();
    cache(['winx_locations' => $winx_locations], now()->addMinutes(10));
}
$winx_pickup = cache('winx_pickup');
if(!$winx_pickup){
    $winx_pickup = $winx->getPickup();
    cache(['winx_pickup' => $winx_pickup], now()->addMinutes(10));
}

$__due = $total - $item->partial_payment;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap');
    .rubber-paid {border: 5px solid #003deb;border-radius: 2px;display: inline-block;padding: 8px;color: #003deb;font-size: 60px;font-family: 'Black Ops One', cursive;text-transform: uppercase;text-align: center;opacity: 0.6;width: auto;transform: rotate(-25deg);position: absolute;top: 40%;left: 30%;}
    .rubber-unpaid {border: 5px solid red;border-radius: 2px;display: inline-block;padding: 8px;color: red;font-size: 60px;font-family: 'Black Ops One', cursive;text-transform: uppercase;text-align: center;opacity: 0.6;width: auto;transform: rotate(-25deg);position: absolute;top: 40%;left: 30%;}
</style>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="row">
            <div class="col-md-8">
                @if($item->note)
                <div class="alert alert-custom alert-primary" role="alert">
                    <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
                    <div class="alert-text">Note: <strong>{{ $item->note }}</strong></div>
                </div>
                @endif
                <div class="card card-custom gutter-b">
                    <div class="card-header card-custom {{ ($item->status==4) ? 'bg-success' :'' }} pt-3 ribbon ribbon-right">
                        <h3 class="card-title align-items-start flex-column">
                            <div class="ribbon-target {{ ($paid) ? 'bg-success' : '' }} {{ ($partial) ? 'bg-warning' : '' }} {{ ($paid+$partial) ? '' : 'bg-danger' }} pt-3 ribbon ribbon-right" style="top: 100px; right: -2px;">
                                @if($paid) PAID @elseif($partial) PARTIAL PAID @else UNPAID @endif
                            </div>
                            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Invoice #{{ $item->id }} - {{ $barcode }} - {{ $item->created_at->format('d F Y') }}</span>
                            <span class="{{ ($item->status==4) ? 'text-white' :'text-muted' }}  mt-3 mb-3 font-weight-bold font-size-lg">C: {{ $item->created_at }} / U: {{ $item->updated_at }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-primary" onclick="printa4('customer');"><i class="fas fa-print"></i> Print (C)</button>
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a class="dropdown-item" href="#" onclick="printa4('office');">Office Copy Print</a>
                                    <a class="dropdown-item" href="#" onclick="printa4('customer');">Customer Copy Print</a>
                                    <a class="dropdown-item" href="#" onclick="printa4('packing_list');">Packing Copy Print</a>
                                    <a class="dropdown-item" href="#" onclick="printa4('shipping');">Shipping Copy Print</a>
                                    <a class="dropdown-item" href="#" onclick="printa4('all');">All Copy Print</a>
                                </div>
                            </div>
                            {{-- <a href="" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-print"></i> Print</a> --}}
                        </div>
                    </div>

                    <div class="card-body pt-6">
                        @include('msg')
                        <div id="print-body">
                            <table class="table no-border">
                                <tr>
                                    <td width="30%">
                                        <h6 class="font-weight-medium font-size-22 mb-3">Billing Address <span class="no-print">(<a href="javascript:{editBilling();}">Edit</a>)</span></h6>
                                        <address class="d-flex flex-column mb-0">
                                            <span class="text-gray-600 font-size-2">{{ @$item->billing_address->name }}</span>
                                            <span class="text-gray-600 font-size-2">{{ implode(", ", array_filter([@$item->billing_address->mobile,@$item->billing_address->mobile2])) }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->billing_address->street }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->billing_address->district }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @implode(', ',array_filter([@$item->billing_address->city, @$item->billing_address->postcode])) }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->billing_address->country }}</span>
                                        </address>
                                    </td>
                                    <td width="30%">
                                        <h6 class="font-weight-medium font-size-22 mb-3">Shipping Address <span class="no-print">(<a href="javascript:{editShipping();}">Edit</a>)</span></h6>
                                        <address class="d-flex flex-column mb-0">
                                            <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->name }}</span>
                                            <span class="text-gray-600 font-size-2">{{ implode(", ", array_filter([@$item->shipping_address->mobile,@$item->shipping_address->mobile2])) }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->street }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->district }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @implode(', ',array_filter([@$item->shipping_address->city, @$item->shipping_address->postcode])) }}</span>
                                            <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->country }}</span>
                                        </address>
                                    </td>
                                    
                                </tr>
                            </table>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="pl-3 font-weight-bold">Description</th>
                                            <th class="text-right font-weight-bold">QNT</th>
                                            <th class="text-right font-weight-bold hide-pack">Rate</th>
                                            <th class="text-right pr-3 font-weight-bold hide-pack">Amount</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                        @foreach($item->metas as $meta)
                                        @if($meta->book_id)
                                        <tr>
                                            <td>
                                                <h6 class="font-size-2 font-weight-normal mb-1 pl-0">
                                                    {{ $meta->product->title_bn }} <br> {{ $meta->product->author_bn }}
                                                    @if(@$meta->product->pre_order)
                                                    <span class="badge badge-info">Pre-Order</span>
                                                    @endif
                                                </h6>
                                                <span class="font-size-2 text-gray-600">
                                                    @php $pro_de = @implode(', ', array_filter([@$meta->product->type,@$meta->product->language])); @endphp
                                                    {!! ($pro_de) ? $pro_de.'<br>' : '' !!}
                                                </span>
                                                <span class="font-size-2 text-gray-600">Seller: {{ @$meta->vendor->name }} | S >> {{ $meta->book->shelf }}</span>
                                            </td>
                                            <td class="text-right"><span class="font-size-2 ml-4 ml-md-8">{{ $meta->quantity }}</span>@if($meta->status==7)<div class="no-print"><i class="fa fa-check text-success"></i></div>@endif</td>
                                            <td class="text-right"><span class="font-size-2 ml-4 ml-md-8 hide-pack">৳ @if($meta->product->rate>$meta->rate)<s>{{ $meta->product->rate }}</s>@endif {{ $meta->rate }}</span></span></td>
                                            <td class="text-right"><span class="font-weight-medium font-size-2 hide-pack">৳ @money($meta->rate * $meta->quantity)</span></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot class="hide-pack">
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Subtotal</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($item->total)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Gift Wrap</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($item->gift_wrap)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Shipping</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($item->shipping)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Coupon Discount</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($item->coupon_discount)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Total</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($total)</th>
                                        </tr>
                                        @if($item->partial_payment>0)
                                        <tr>
                                            <th colspan="3" class="pl-2 font-weight-bold text-right">Due</th>
                                            <th class="text-right pr-2 font-weight-bold">৳ @money($total - $item->partial_payment)</th>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>

                            @if($item->note)
                            <div class="pt-2"><strong>Note: </strong>{{ $item->note }}</div>
                            @endif
                        </div>

                        @if($item->tracking)
                        <div id="winx-tracking">{{ str_replace(['WINX:'],'',$item->tracking) }}</div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom gutter-b">
                    <div class="card-body p-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Invoice Action</span>
                        </h3>
                        <div class="accordion accordion-toggle-arrow" id="acr_pr">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" data-toggle="collapse" data-target="#arc_1">
                                        <i class="fa fa-bags-shopping"></i> System Note
                                    </div>
                                </div>
                                <div id="arc_1" class="collapse show" data-parent="#acr_pr">
                                    <div class="card-body">
                                        <ul>
                                        @if($item->system_note)
                                        @foreach($item->system_note as $_note)
                                        <li>{{ $_note }}</li>
                                        @endforeach
                                        @endif
                                        </ul> 
                                        <form action="" method="POST">
                                            @method('put')
                                            @csrf
                                            <input name="system_note" id="system_note" rows="3" class="form-control" placeholder="Just Type Note Then Press Enter" required>
                                            <button type="submit" class="mt-2 btn btn-primary">Save Note</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" data-toggle="collapse" data-target="#acr_2">
                                        <i class="fa fa-shipping-fast"></i> Order & Shipment
                                    </div>
                                </div>
                                <div id="acr_2" class="collapse show" data-parent="#acr_pr">
                                    <div class="card-body">
                                        @if($ssl || $item->partial_payment>0)
                                        <div class="card card-custom gutter-b bg-success">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                    <div class="d-flex flex-column mr-5">
                                                        <a href="#" class="h4 text-white mb-5">
                                                            Payment Details
                                                        </a>
                                                        <p class="text-white">
                                                            @if($ssl)
                                                            {!! str_replace("|", '<br>', $ssl) !!}
                                                            @endif
                                                            @if($item->partial_payment>0)
                                                            Partial Payment for COD {{ $item->partial_payment }} TK.
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($item->status<3)
                                        <form class="row" action="" method="POST">
                                            @method('put')
                                            @csrf
                                            <x-form::select column="6" name="pickup_id" title="Select Pickup Location" :required="true" placeholder="Select Pickup Location" value="" :options="[]"/>
                                            <x-form::select column="6" name="package" title="Select Pickup Package" :required="true" placeholder="Select Package" value="" :options="[]"/>
                                            <x-form::select column="12" name="delivery_area" title="Select Delivery Location" :required="true" placeholder="Select Delivery Location" value="" :options="[]"/>
                                            <div class="col-md-6 mb-3">
                                                <button class="btn btn-success" type="submit">Ship with WINX</button>
                                            </div>
                                        </form>
                                        @endif

                                        <form class="row" action="" method="POST">
                                            @method('put')
                                            @csrf
                                            <x-form::input column="12" name="tracking" title="Shipment Tracking ID" :required="false" placeholder="Enter Tracking ID" value="{{ $item->tracking }}"/>
                                            <x-form::select column="6" name="status" title="Order Status" :required="true" :options="$status" placeholder="Select Option" value="{{ $item->status }}"/>
                                            <x-form::select column="6" name="payment" title="Payment Method" :required="true" :options="$pm" placeholder="Select Option" value="{{ $item->payment }}"/>
                                            <div class="col-md-6">
                                                <button class="btn btn-success" type="submit">Update Order</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if(@$auth->role=='admin')
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#acr_3">
                                        <i class="fa fa-sack-dollar"></i> Partial Payment
                                    </div>
                                </div>
                                <div id="acr_3" class="collapse" data-parent="#acr_pr">
                                    <div class="card-body">
                                        <form class="row" action="" method="POST">
                                            @method('put')
                                            @csrf
                                            <x-form::input column="12" name="partial_payment" title="bKash / Rokect / Nagad TrxID or Note" :required="true" placeholder="Enter TrxID" value=""/>
                                            <x-form::input column="6" name="partial_amount" title="Amount" :required="true" placeholder="Enter Amount" type="number" step="0.01" value=""/>
                                            <x-form::input column="6" name="shipping" title="Shipping" :required="true" placeholder="Enter Shipping" type="number" step="0.01" value="{{ $item->shipping }}"/>
                                            <div class="col-md-6">
                                                <button class="btn btn-success" type="submit">Add Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#acr_4">
                                        <i class="fa fa-envelope-open"></i> Email & SMS
                                    </div>
                                </div>
                                <div id="acr_4" class="collapse" data-parent="#acr_pr">
                                    <div class="card-body text-center">
                                        @foreach($emails as $em_key => $em)
                                        @if($em)
                                        <a href="?mail={{ $em_key }}" class="btn btn-info mr-3 mb-3">Send {{ $em }} Email & SMS</a>
                                        @endif
                                        @endforeach
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

<div id="print-data" style="display: none;">
    <div class="single-page" id="main_invoice">
        <div id="inv-head">
            <table class="table no-border mb-10">
                <tr style="padding: 0;">
                    <td style="width:25mm;">
                        <img src="{{ asset('assets/images/wb-logo-sq-color.svg') }}" alt="">
                    </td>
                    <td style="vertical-align: bottom !important;">
                        <strong class="display-4 text-black font-weight-boldest mb-10">Boiferry Limited</strong><br>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">House-11 (1<sup>st</sup> Floor), Road-3,</span>
                            <span class="text-gray-600 font-size-2">Block-H, Avenue-7</span>
                            <span class="text-gray-600 font-size-2">Banasree, Dhaka-1219</span>
                            <span class="text-gray-600 font-size-2">(880) 96 7822 8228</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">&nbsp;</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">Invoice Number</span>
                            <span class="text-gray-600 font-size-2">Invoice Date</span>
                            <span class="text-gray-600 font-size-2">Payment Method</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">INVOICE</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">: {{ $barcode }}</span>
                            <span class="text-gray-600 font-size-2">: {{ $item->created_at->format('d M, Y') }}</span>
                            <span class="text-gray-600 font-size-2">: {{ strtoupper($item->payment) }}</span>
                        </address>
                    </td>
                </tr>
            </table>
        </div>
        <div class="inv-body"></div>
        @if($paid)
        <div class="rubber-paid">FULL PAID</div>
        @else
        <div class="rubber-unpaid">DUE: @money($__due)</div>
        @endif
        <div id="inv-foot">
            <div class="text-center">
                Thanks for Shopping at Boiferry Limited
            </div>
        </div>
    </div>

    <div class="single-page" id="office_invoice">
        <div id="inv-head">
            <table class="table no-border mb-10">
                <tr style="padding: 0;">
                    <td style="width:25mm;">
                        <img src="{{ asset('assets/images/wb-logo-sq-color.svg') }}" alt="">
                    </td>
                    <td style="vertical-align: bottom !important;">
                        <strong class="display-4 text-black font-weight-boldest mb-10">Boiferry Limited</strong><br>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">House-11 (1<sup>st</sup> Floor), Road-3,</span>
                            <span class="text-gray-600 font-size-2">Block-H, Avenue-7</span>
                            <span class="text-gray-600 font-size-2">Banasree, Dhaka-1219</span>
                            <span class="text-gray-600 font-size-2">(880) 96 7822 8228</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">OFFICE</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">Invoice Number</span>
                            <span class="text-gray-600 font-size-2">Invoice Date</span>
                            <span class="text-gray-600 font-size-2">Payment Method</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">INVOICE</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="text-gray-600 font-size-2">: {{ $barcode }}</span>
                            <span class="text-gray-600 font-size-2">: {{ $item->created_at->format('d M, Y') }}</span>
                            <span class="text-gray-600 font-size-2">: {{ strtoupper($item->payment) }}</span>
                        </address>
                    </td>
                </tr>
            </table>
        </div>
        <div class="inv-body"></div>
        @if($paid)
        <div class="rubber-paid">FULL PAID</div>
        @else
        <div class="rubber-unpaid">DUE: @money($__due)</div>
        @endif
        <div id="inv-foot">
            <div class="text-center">
                Thanks for Shopping at Boiferry Limited
            </div>
        </div>
    </div>

    <div class="single-page" id="packing_list">
        <h2>Invoice# {{ $barcode }}</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="pl-3 font-weight-bold">Description</th>
                        <th class="text-right font-weight-bold">QNT</th>
                        <th class="text-right font-weight-bold">Seller</th>
                        <th class="text-right font-weight-bold">Shelf</th>
                    </tr>
                </thead>
                 <tbody>
                    @foreach($item->metas as $meta)
                    @if($meta->book_id)
                    <tr>
                        <td>
                            <h6 class="font-size-2 font-weight-normal mb-1 pl-0">{{ $meta->product->title_bn }} <br> {{ $meta->product->author_bn }}</h6>
                            <span class="font-size-2 text-gray-600">
                                @php $pro_de = @implode(', ', array_filter([@$meta->product->type,@$meta->product->language])); @endphp
                                {!! ($pro_de) ? $pro_de.'<br>' : '' !!}
                            </span>
                        </td>
                        <td class="text-right"><span class="font-size-2 ml-4 ml-md-8">{{ $meta->quantity }}</span></td>
                        <td class="text-right"><span class="font-size-2 ml-4 ml-md-8">{{ $meta->vendor->name }}</span></td>
                        <td class="text-right">{{ @$item->book->shelf }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="single-page" id="shipping_label">
        <table class="table table-bordered">
            <tr>
                <td>
                    <svg id="barcode"></svg>
                </td>
                <td>
                    <strong style="text-align: right; font-size: 40px;float:right;">Invoice ID#{{ $barcode }}</strong>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <strong>From:</strong><br>
                    <strong class="display-4 text-black font-weight-boldest mb-10">Boiferry Limited</strong><br>
                    <address class="d-flex flex-column mb-0">
                        <span class="text-gray-600 font-size-2">House-11 (1<sup>st</sup> Floor), Road-3,</span>
                        <span class="text-gray-600 font-size-2">Block-H, Avenue-7</span>
                        <span class="text-gray-600 font-size-2">Banasree, Dhaka-1219</span>
                    </address>
                    <strong>Mobile:<br>(880) 96 7822 8228</strong>
                </td>
                <td width="50%">
                    <strong>To:</strong><br>
                    <address class="d-flex flex-column mb-0">
                        <span class="text-gray-600 font-size-2"><strong>{{ @$item->shipping_address->name }}</strong></span>
                        <span class="text-gray-600 font-size-2"><strong>{{ implode(", ", array_filter([@$item->shipping_address->mobile,@$item->shipping_address->mobile2])) }}</strong></span>
                        <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->street }}</span>
                        <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->district }}</span>
                        <span class="text-gray-600 font-size-2">{{ @implode(', ',array_filter([@$item->shipping_address->city, @$item->shipping_address->postcode])) }}</span>
                        <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->country }}</span>
                    </address>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 40px; text-align: center;">
                    <strong>
                    @if($paid)
                    Full Paid
                    @else
                    COD: @money($total - $item->partial_payment) TK
                    @endif
                    </strong>
                </td>
            </tr>
            @if($item->tracking)
            <tr>
                <td colspan="100%" style="text-align:center;">
                    <svg id="tracking_barcode"></svg>
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>

<form action="" method="POST" id="address_form">
    @csrf
    @method('put')
    <div class="modal fade" id="address-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="address_modal_name"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <x-form::input column="6" name="name" title="Name" :required="true" type="text" value="" />
                        <x-form::input column="6" name="company" title="Company" :required="false" type="text" value="" />
                        <x-form::input column="12" name="street" title="Street" :required="true" type="text" value="" />
                        <x-form::input column="4" name="country" title="Country" :required="true" type="text" value="" />
                        <x-form::input column="4" name="city" title="City" :required="true" type="text" value="" />
                        <x-form::input column="4" name="district" title="District" :required="true" type="text" value="" />
                        <x-form::input column="4" name="postcode" title="Postcode" :required="false" type="text" value="" />
                        <x-form::input column="4" name="mobile" title="Mobile" :required="true" type="tel" value="" />
                        <x-form::input column="4" name="mobile2" title="Mobile 2" :required="false" type="tel" value="" />
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('assets/admin/js/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
@if($item->tracking)
<script type="text/javascript" src="//winx.com.bd/assets/js/embeded.js?time={{ time() }}"></script>
@endif
<script>
    JsBarcode("#barcode", "{{ $barcode }}", {
        format: "EAN8",
        lineColor: "#000",
        width:3,
        height:60,
        displayValue: true
    });
    @if($item->tracking)
    JsBarcode("#tracking_barcode", "{{ str_replace(['WINX:'],'',$item->tracking) }}", {
        format: "CODE128",
        lineColor: "#000",
        width:3,
        height:60,
        displayValue: true
    });
    @endif
    function printa4(type){
        if(type=='shipping'){
            $("#main_invoice").hide();
            $("#office_invoice").hide();
            $("#shipping_label").show();
            $("#packing_list").hide();
        }
        if(type=='all'){
            $("#main_invoice").show();
            $("#office_invoice").show();
            $("#shipping_label").show();
            $("#packing_list").show();
        }
        if(type=='office'){
            $("#main_invoice").hide();
            $("#office_invoice").show();
            $("#shipping_label").hide();
            $("#packing_list").hide();
        }
        if(type=='customer'){
            $("#main_invoice").show();
            $("#office_invoice").hide();
            $("#shipping_label").hide();
            $("#packing_list").hide();
        }
        if(type=='packing_list'){
            $("#packing_list").show();
            $("#office_invoice").hide();
            $("#shipping_label").hide();
            $("#main_invoice").hide();
        }
        $(".inv-body").html($("#print-body").html());
        // $("#inv-footer").html($("#page_footer").html());
        $("#print-data").show();
        $("#print-data").print({
            addGlobalStyles : true,
            stylesheet : "{{ asset('assets/admin/css/print.css') }}",
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
        $("#print-data").hide();
        $.ajax({
            method: "GET",
            url: "{{ url('invoice/'.$item->id) }}?print=yes",
            success: function(data){
                
            },error: function(data){
        
            }
        });
    }

    @if(isset($print) && $print==true)
    printa4('all');
    @endif

    function editBilling(){
        $("#address-modal").modal('show');
        $("#address_modal_name").html("Edit Billing Address");
        var data = {!! $item->billing_address !!};
        $("#address_form").attr("action", "{{ url('address/') }}/" + data.id);
        $.each(data, function(i,v){
            $("#address_form #"+ i).val(v);
        });
    }

    function editShipping(){
        $("#address-modal").modal('show');
        $("#address_modal_name").html("Edit Shipping Address");
        var data = {!! $item->shipping_address !!};
        $("#address_form").attr("action", "{{ url('address/') }}/" + data.id);
        $.each(data, function(i,v){
            $("#address_form #"+ i).val(v);
        });
    }

    function lockInvoice(){
        $.ajax({
            method: "POST",
            url: "{{ url('invoice/'.$item->id) }}",
            data: {lock: 'yes','_method': 'PUT'},
            success: function(data){
        
            },error: function(data){
        
            }
        });
    }

    var pickup_id_select = $("#pickup_id").select2({
        'theme': 'bootstrap',
        'data': {!! json_encode(@$winx_pickup->results) !!}
    });

    var delivery_area_select = $("#delivery_area").select2({
        'theme': 'bootstrap',
        'data': {!! json_encode(@$winx_locations->results) !!}
    });

    var package_select = $("#package").select2({
        'theme': 'bootstrap',
        'data': {!! json_encode(@$winx_package->results) !!}
    });

    $(document).ready(function() {
    @if($auth->role!='admin')
        @if(@$lock==false)
        lockInvoice();
        setTimeout(lockInvoice, 1000*30);
        @else
        let timerInterval
        Swal.fire({
            icon: 'info',
            allowOutsideClick: false,
            title: 'Invoice is locked by {{ $item->lock->name }}',
            html: 'Page will reload in <b></b> seconds.',
            timer: 1000*15,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                    var time = Swal.getTimerLeft() / 1000;
                    b.textContent = time.toFixed(0);
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                location.reload();
            }
        });
        @endif
    @endif 
    });
</script>
@endpush