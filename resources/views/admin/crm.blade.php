@extends('layouts.admin')

@section('content')
@php
$status = ['-','Pending','Processing / Paid','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
@endphp
<div class="content d-flex flex-column flex-column-fluid">

    <div class="subheader py-6 py-lg-8 subheader-transparent">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard <span class="printer_status text-danger">(Printer <span id="printer_status">N/A</span>)</span></h5>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap">
                <a href="#" class="btn btn-fixed-height btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 my-1 mr-3" id="kt_dashboard_daterangepicker" data-toggle="tooltip" title="" data-placement="top" data-original-title="Select dashboard daterange">
                    <span class="opacity-60 font-weight-bolder mr-2" id="kt_dashboard_daterangepicker_title">Today:</span>
                    <span class="font-weight-bolder">{{ date("D, M d") }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column-fluid">
        <div class="container">
            
            <div class="row">
                @if($role=='crm')
                <div class="col-xl-12">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Invoice</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-lg">Call List</span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark">
                                    <li class="nav-item ml-0">
                                        <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm" data-toggle="tab" href="#kt_tab_list_4_1">COD</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm active" data-toggle="tab" href="#kt_tab_list_4_2">PAID</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2">
                            <div class="tab-content mt-5" id="myTabList4">
                                <div class="tab-pane fade" id="kt_tab_list_4_1" role="tabpanel" aria-labelledby="kt_tab_list_4_1">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 min-w-150px"></th>
                                                    <th class="p-0 min-w-140px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cod as $item)
                                                <tr>
                                                    <td class="pl-0 text-left">
                                                        <a href="{{ url('invoice',$item->id) }}" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $item->id }}. {{ $item->user->name }}</a>
                                                        <span class="text-muted font-weight-bold d-block">Successful Fellas</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-bold d-block">Total</span>
                                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg">৳ {{ $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-500"><a href="sip://{{ $item->user->mobile }}">{{ $item->user->mobile }}</a></span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="label label-lg label-light-info label-inline">{{ @$status[$item->status] }}</span>
                                                    </td>
                                                    <td class="text-right pr-0">
                                                        <a href="{{ url('invoice',$item->id) }}" class="btn btn-light btn-sm"> view </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Tap pane-->
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade active show" id="kt_tab_list_4_2" role="tabpanel" aria-labelledby="kt_tab_list_4_2">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 min-w-150px"></th>
                                                    <th class="p-0 min-w-140px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($paid as $item)
                                                <tr>
                                                    <td class="pl-0 text-left">
                                                        <a href="{{ url('invoice',$item->id) }}" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $item->id }}. {{ $item->user->name }}</a>
                                                        <span class="text-muted font-weight-bold d-block">Successful Fellas</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-bold d-block">Total</span>
                                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg">৳ {{ $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-500"><a href="sip://{{ $item->user->mobile }}">{{ $item->user->mobile }}</a></span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="label label-lg label-light-info label-inline">{{ @$status[$item->status] }}</span>
                                                    </td>
                                                    <td class="text-right pr-0">
                                                        <a href="{{ url('invoice',$item->id) }}" class="btn btn-light btn-sm"> view </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Tap pane-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                @endif
                @if($role=='logistics')
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Mark as Shipped</h3>
                            <input type="text" id="shipped" class="form-control" placeholder="scan barcode..." autofocus>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Mark as Picked</h3>
                            <input type="text" id="picked" class="form-control" placeholder="scan barcode...">
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Invoice</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-lg">Shipment List</span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark">
                                    <li class="nav-item ml-0">
                                        <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm" data-toggle="tab" href="#kt_tab_list_4_1">Half Packed</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm active" data-toggle="tab" href="#kt_tab_list_4_2">Packed</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2">
                            <div class="tab-content mt-5" id="myTabList4">
                                <div class="tab-pane fade" id="kt_tab_list_4_1" role="tabpanel" aria-labelledby="kt_tab_list_4_1">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 min-w-150px"></th>
                                                    <th class="p-0 min-w-140px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($half as $item)
                                                <tr>
                                                    <td class="pl-0 text-left">
                                                        <a href="{{ url('invoice',$item->id) }}" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $item->id }}. {{ $item->user->name }}</a>
                                                        <span class="text-muted font-weight-bold d-block">Successful Fellas</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-bold d-block">Total</span>
                                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg">৳ {{ $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-500"><a href="sip://{{ $item->user->mobile }}">{{ $item->user->mobile }}</a></span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="label label-lg label-light-info label-inline">{{ @$status[$item->status] }}</span>
                                                    </td>
                                                    <td class="text-right pr-0">
                                                        <a href="{{ url('invoice',$item->id) }}" class="btn btn-light btn-sm"> view </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Tap pane-->
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade active show" id="kt_tab_list_4_2" role="tabpanel" aria-labelledby="kt_tab_list_4_2">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 min-w-150px"></th>
                                                    <th class="p-0 min-w-140px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-110px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($full as $item)
                                                <tr>
                                                    <td class="pl-0 text-left">
                                                        <a href="{{ url('invoice',$item->id) }}" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $item->id }}. {{ $item->user->name }}</a>
                                                        <span class="text-muted font-weight-bold d-block">Successful Fellas</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-bold d-block">Total</span>
                                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg">৳ {{ $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-muted font-weight-500"><a href="sip://{{ $item->user->mobile }}">{{ $item->user->mobile }}</a></span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="label label-lg label-light-info label-inline">{{ @$status[$item->status] }}</span>
                                                    </td>
                                                    <td class="text-right pr-0">
                                                        <a href="{{ url('invoice',$item->id) }}" class="btn btn-light btn-sm"> view </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Tap pane-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                
                @endif
            </div>

        </div>
    </div>
    

</div>

@php
$item = App\Models\Invoice::find(request()->dp);
if($item){
$paid = $ssl = false;
$sn = json_encode($item->system_note);
if(preg_match("/sslcommerz_card\#([0-9a-z_\|\s,\.]+)\"/i", $sn, $match)){
    $paid = true;
    $ssl = $match[1];
}
$total = $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount;
$status = ['-','Pending','Processing / Paid','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$emails = ['', '', 'Processing / Paid','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$pm = ['cod'=> 'CoD (100TK Advance)','cod-full'=>"COD (0 Advance)",'sslcommerz'=>'Sslcommerz (Full)'];
}
@endphp

@if($item)
<div id="print-data" style="display: none;">
    <div id="thermal">
        <div style="width:5.98in;">
            <div style="text-align: center;width: 100%; padding-top: 10mm; padding-bottom: 3mm;"><img src="{{ asset('assets/images/logos/boiferry-main-black.svg') }}" alt="" style="height: .6in;"></div>
            <table style="width:100%;border: 2px solid black;border-collapse: collapse;" border="2">
                <tr>
                    <td style="border-bottom: 2px solid black;">
                        <svg id="barcode"></svg>
                    </td>
                    <td style="border-left: 2px solid black;border-bottom: 2px solid black;">
                        <strong style="text-align: right; font-size: 20px;float:right;">Invoice#{{ ean8(1000000+$item->id) }}&nbsp;&nbsp;</strong>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom: 2px solid black;">
                        <div style="padding:5px;">
                            <strong>From:</strong><br>
                            <strong>Boiferry Limited</strong><br>
                            House-11 (1st Floor), Road-3,<br>
                            Block-H, Avenue-7,<br>
                            Banasree, Dhaka-1219<br>
                            <strong>
                                Mobile/Hotline:<br>
                                (880) 96 7822 8228
                            </strong>
                        </div>
                    </td>
                    <td width="50%" style="border-left: 2px solid black;border-bottom: 2px solid black;">
                        <div style="padding:5px;">
                            <strong>To:</strong><br>
                            <strong>{{ @$item->shipping_address->name }}</strong><br>
                            <strong>{{ implode(", ", array_filter([@$item->shipping_address->mobile,@$item->shipping_address->mobile2])) }}</strong><br>
                            {{ @$item->shipping_address->street }}<br>
                            {{ @$item->shipping_address->district }}<br>
                            {{ @implode(', ',array_filter([@$item->shipping_address->city, @$item->shipping_address->postcode])) }}<br>
                            {{ @$item->shipping_address->country }}
                        </div>
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
            </table>
        </div>
    </div>
</div>
@endif
@endsection


@push('scripts')
<script type="text/JavaScript" src="{{ asset('assets/admin/js/JsBarcode.ean-upc.min.js') }}"></script>
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script type="text/JavaScript" src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.js"></script>
<script>
    
    function printa4(){
        $("#thermal").show();
        $(".inv-body").html($("#print-body").html());
        $("#print-data").show();
        $("#print-data").print({
            addGlobalStyles : true,
            stylesheet : "{{ asset('assets/admin/css/print-4-6.css') }}",
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
        $("#print-data").hide();
    }


    function directPrint(){
        // var config = qz.configs.create("", {
        var config = qz.configs.create("Rongta RP4xx Series", {
            units: 'in',
            orientation: 'landscape',
            size: {width: 4, height: 6},
            colorType: 'blackwhite', 
            interpolation: "nearest-neighbor",
            density: 203,
        });
        var data = [{
           type: 'pixel',
           format: 'html',
           flavor: 'plain',
           data: '<html><body>' + $("#thermal").html() + '</body></html>',
        }];
        qz.print(config, data).catch(function(e) { console.error(e); });
    }

    function markAsShipped(barcode){
        $.ajax({
            method: "POST",
            url: "{{ url('invoice/shipped') }}",
            data: {barcode: barcode},
            success: function(data){
                window.location = "{{ url('/') }}?dp=" + data.id;
            },error: function(data){
                alert("Invoice Not Found!!");
            }
        });
    }

    $(document).ready(function() {
        $("#shipped").on('change', function(){
            var data = $(this).val();
            if(data.length==8){
                markAsShipped(data);
            }
        });
        @if(request()->dp)
        JsBarcode("#barcode", "{{ ean8(1000000+$item->id) }}", {
            format: "EAN8",
            lineColor: "#000",
            width: 1.5,
            height: 50,
            displayValue: false
        });
        @endif
        qz.security.setCertificatePromise(function(resolve, reject) {
           fetch("{{ url('assets/digital-certificate.txt') }}", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
              .then(function(data) { data.ok ? resolve(data.text()) : reject(data.text()); });
        });
        qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
                fetch("{{ url('invoice/sign') }}?request=" + toSign, {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
                .then(function(data) { data.ok ? resolve(data.text()) : reject(data.text()); });
            };
        });
        qz.websocket.connect().then(function() {
            $("#printer_status").html("Connected");
            $(".printer_status").removeClass('text-danger').addClass('text-success');
            @if(request()->dp)
            directPrint();
            @endif
        });
             
    });
</script>
@endpush