{{-- BTL Template - Do not delete --}}
@extends('layouts.seller')
@section('title','Invoice#'.$item->invoice_id)
@section('content')
@php $invoice = $item->invoice; $sub_total = 0; @endphp
<style>
    .bangla{
        font-family: 'Hind Siliguri', 'SolaimanLipi',Helvetica,"sans-serif";
    }
    .f-shipping .font-size-2{
        font-size: 18px;
    }
    .f-shipping .font-size-3{
        font-size: 22px;
    }
</style>
<div class="d-flex flex-column-fluid bangla">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Invoice #{{ $item->invoice_id }}</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Seller Center Invoice</span>
                </h3>
                <div class="card-toolbar">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="printa4('all');"><i class="fa fa-print"></i> Print All</button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="printDrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                            <span class="sr-only">Options</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="printDrop">
                            <a class="dropdown-item" href="javascript:{printa4('picklist')}">Print Picklist</a>
                            <a class="dropdown-item" href="javascript:{printa4('shipping')}">Print Shipping Label</a>
                            <a class="dropdown-item" href="javascript:{printa4('invoice')}">Print Invoice</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                <div id="print-body">
                    {{-- <table class="table no-border">
                        <tr>
                            <td width="30%">
                                <h6 class="font-weight-medium font-size-22 mb-3">Billing Address</h6>
                                <address class="d-flex flex-column mb-0">
                                    <span class="font-size-3">{{ @$invoice->billing_address->name }}</span>
                                    <span class="font-size-3">{{ implode(", ", array_filter([@$invoice->billing_address->mobile,@$invoice->billing_address->mobile2])) }}</span>
                                    <span class="font-size-2">{{ @$invoice->billing_address->street }}</span>
                                    <span class="font-size-2">{{ @$invoice->billing_address->district }}</span>
                                    <span class="font-size-2">{{ @implode(', ',array_filter([@$invoice->billing_address->city, @$invoice->billing_address->postcode])) }}</span>
                                    <span class="font-size-2">{{ @$invoice->billing_address->country }}</span>
                                </address>
                            </td>
                            <td width="30%">
                                <h6 class="font-weight-medium font-size-22 mb-3">Shipping Address</h6>
                                <address class="d-flex flex-column mb-0">
                                    <span class="font-size-2">{{ @$invoice->shipping_address->name }}</span>
                                    <span class="font-size-2">{{ implode(", ", array_filter([@$invoice->shipping_address->mobile,@$invoice->shipping_address->mobile2])) }}</span>
                                    <span class="font-size-2">{{ @$invoice->shipping_address->street }}</span>
                                    <span class="font-size-2">{{ @$invoice->shipping_address->district }}</span>
                                    <span class="font-size-2">{{ @implode(', ',array_filter([@$invoice->shipping_address->city, @$invoice->shipping_address->postcode])) }}</span>
                                    <span class="font-size-2">{{ @$invoice->shipping_address->country }}</span>
                                </address>
                            </td>
                        </tr>
                    </table> --}}

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="font-size-2 pl-3 font-weight-bold">Description</th>
                                    <th class="font-size-2 text-right font-weight-bold">QNT</th>
                                    <th class="font-size-2 text-right font-weight-bold hide-pack">Rate</th>
                                    <th class="font-size-2 text-right pr-3 font-weight-bold hide-pack">Amount</th>
                                </tr>
                            </thead>
                             <tbody>
                                @foreach($item->metas as $meta)
                                @if($meta->book_id)
                                <tr>
                                    <td>
                                        <h6 class="font-size-2 font-weight-normal mb-1 pl-0">{{ $meta->product->title_bn }} <br> {{ $meta->product->author_bn }}</h6>
                                        <span class="font-size-2 text-gray-600">
                                            @php $pro_de = @implode(', ', array_filter([@$meta->product->type,@$meta->product->language])); $sub_total += ($meta->rate * $meta->quantity); @endphp
                                            {!! ($pro_de) ? $pro_de.'<br>' : '' !!}
                                        </span>
                                    </td>
                                    <td class="text-right"><span class="font-size-2 ml-4 ml-md-8">{{ $meta->quantity }}</span></td>
                                    <td class="text-right"><span class="font-size-2 ml-4 ml-md-8 hide-pack">৳ @if($meta->product->rate>$meta->rate)<s>{{ $meta->product->rate }}</s>@endif {{ $meta->rate }}</span></span></td>
                                    <td class="text-right"><span class="font-weight-medium font-size-2 hide-pack">৳ @money($meta->rate * $meta->quantity)</span></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot class="hide-pack">
                                <tr>
                                    <th colspan="3" class="text-right">Sub Total</th>
                                    <th class="text-right">@money($sub_total)</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Coupon Discount</th>
                                    <th class="text-right">@money($item->coupon_discount)</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right">@money($sub_total - $item->coupon_discount)</th>
                                </tr>
                                @if($invoice->note)
                                <tr>
                                    <td colspan="100%" class="pt-10 pb-10 font-size-2"><strong>Note: </strong> {{ $invoice->note }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
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
                        <strong class="display-4 text-black font-weight-boldest mb-10">বইফেরী</strong><br>
                        <address class="d-flex flex-column mb-0">
                            <span class="font-size-2">3<sup>rd</sup> Floor, Abedin Bhaban, Soni Akra</span>
                            <span class="font-size-2">Dhaka 1236, Bangladesh</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">&nbsp;</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="font-size-2">Invoice Number</span>
                            <span class="font-size-2">Invoice Date</span>
                            <span class="font-size-2">Payment Method</span>
                        </address>
                    </td>
                    <td width="15%" style="vertical-align: top !important;">
                        <h6 class="display-4 text-black font-weight-boldest mb-2">INVOICE</h6>
                        <address class="d-flex flex-column mb-0">
                            <span class="font-size-2">: {{ $item->id }}</span>
                            <span class="font-size-2">: {{ $item->created_at->format('d M, Y') }}</span>
                            <span class="font-size-2">: ** ** **</span>
                        </address>
                    </td>
                </tr>
            </table>
        </div>
        <div class="inv-body"></div>
        <div id="inv-foot">
            <div class="text-center">
                Thanks for Shopping at বইফেরী
            </div>
        </div>
    </div>

    <div class="single-page" id="picklist">
        <table class="table no-border">
            <tr>
                <td><strong class="display-4 text-black font-weight-boldest mb-10">SELLER CENTER: PICKLIST</strong></td>
                <td class="text-right"><strong class="display-4 text-black font-weight-boldest mb-10">Invoice# {{ $item->id }}</strong></td>
            </tr>
        </table>
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
                        <td class="text-right">{{ @$item->product->shelf }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="single-page" id="shipping">
        <table class="table table-bordered f-shipping">
            <tr>
                <td>
                    <svg id="barcode"></svg>
                </td>
                <td>
                    <strong style="text-align: right; font-size: 40px;float:right;">Invoice ID#{{ $item->id }}</strong>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <strong>From:</strong><br>
                    <strong class="display-4 text-black font-weight-boldest mb-10">বইফেরী</strong><br>
                    <address class="d-flex flex-column mb-0">
                        <span class="font-size-2">3<sup>rd</sup> Floor, Abedin Bhaban, Soni Akra</span>
                        <span class="font-size-2">Dhaka 1236, Bangladesh</span>
                    </address>
                    <strong class="font-size-2">
                    Mobile:<br>
                    (880) 96 7822 8228, <br>
                    (880) 11 2723 5857
                    </strong>
                </td>
                <td width="50%">
                    {{-- <strong>To:</strong><br>
                    <address class="d-flex flex-column mb-0">
                        <span class="font-size-2"><strong>{{ @$invoice->shipping_address->name }}</strong></span>
                        <span class="font-size-2"><strong>{{ implode(", ", array_filter([@$invoice->shipping_address->mobile,@$invoice->shipping_address->mobile3])) }}</strong></span>
                        <span class="font-size-2">{{ @$invoice->shipping_address->street }}</span>
                        <span class="font-size-2">{{ @$invoice->shipping_address->district }}</span>
                        <span class="font-size-2">{{ @implode(', ',array_filter([@$invoice->shipping_address->city, @$invoice->shipping_address->postcode])) }}</span>
                        <span class="font-size-2">{{ @$invoice->shipping_address->country }}</span>
                    </address> --}}
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection



@push('scripts')
<script type="text/JavaScript" src="{{ asset('assets/admin/js/JsBarcode.ean-upc.min.js') }}"></script>
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script>
    JsBarcode("#barcode", "{{ ean8(1000000+$item->id) }}", {
        format: "EAN8",
        lineColor: "#000",
        width:3,
        height:60,
        displayValue: true
    });
    function printa4(type){
        if(type=='shipping'){
            $("#main_invoice").hide();
            $("#shipping").show();
            $("#picklist").hide();
        }
        if(type=='all'){
            $("#main_invoice").show();
            $("#shipping").show();
            $("#picklist").show();
        }
        if(type=='invoice'){
            $("#main_invoice").show();
            $("#shipping").hide();
            $("#picklist").hide();
        }
        if(type=='picklist'){
            $("#picklist").show();
            $("#shipping").hide();
            $("#main_invoice").hide();
        }
        $(".inv-body").html($("#print-body").html());
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

    @if(request()->inv_print)
    printa4('{{ request()->inv_print }}');
    window.onfocus = function(){ window.close(); }
    @endif
</script>
@endpush