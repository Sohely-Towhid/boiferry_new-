{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Report')
@section('content')
@php
$product = ['All'=>'All','Book'=>'Book','Product'=>'Product'];
$payment = ['All'=>'All','cod-full'=>'COD', 'cod'=>'COD Partial' ,'sslcommerz'=>'SSLCommerz', 'bkash'=>'bKash', 'nagad'=>'Nagad'];
$cod = $sslcommerz = $gt = $shipping = $cd = $gift = $full_cod = $bkash = $nagad = 0; 
$empty = [];
$status= ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fa fa-chart-pie-alt text-info"></i>
                    </span>
                    <h3 class="card-label">
                        Report
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="javascript:{};" onclick="html_table_to_excel('xlsx');" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1 mr-3">Excel</a>
                    <a href="#" class="btn btn-light-primary font-weight-bold" onclick="printa4();">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row">
                        <x-form::input column="3" name="date" title="Date Range" :required="true" type="text" value="{{ request()->get('date', date('Y-m-d')) }}" />
                        {{-- <x-form::select column="2" name="type" title="Item Type" :required="true" type="text" value="" :options="$product" value="{{ request()->get('type', 'All' )}}" /> --}}
                        {{-- <x-form::select column="2" name="vendor_id" title="Vendor" :required="false" type="text" value="" :options="$empty" value="{{ request()->get('vendor_id', 'All' )}}"/> --}}
                        <x-form::select column="2" name="payment" title="Payment Method" :required="false" type="text" value="" :options="$payment" value="{{ request()->get('payment', 'All' )}}"/>
                        <x-form::select column="2" name="status" title="Status" :required="false" type="text" value="" :options="$status" value="{{ request()->get('status', 4 )}}"/>
                        <div class="col">
                            <button class="btn btn-light-primary font-weight-bold mt-8" type="submit"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
                <div class="separator separator-dashed mt-0 mb-5"></div>
                <div id="print-data">
                    <div class="text-center">
                        <h3>{{ $title }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="sales-data">
                            <thead>
                                <tr>
                                    <th>INV</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">S. Total</th>
                                    <th class="text-center">Shipping</th>
                                    <th class="text-center">Coupon Discount</th>
                                    <th class="text-center">Gift Wrap</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-right">Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                @php 
                                $total = $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount;
                                $cod += ($item->payment=='cod') ? $total : 0; 
                                $sslcommerz += ($item->payment=='sslcommerz') ? $total : 0; 
                                $full_cod += ($item->payment=='cod-full') ? $total : 0; 
                                $bkash += ($item->payment=='bkash') ? $total : 0; 
                                $nagad += ($item->payment=='nagad') ? $total : 0; 
                                $gt += $total;
                                $shipping += $item->shipping;
                                $cd += $item->coupon_discount;
                                $gift += $item->gift_wrap;
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td class="text-center">{{ $item->created_at }}</td>
                                    <td class="text-center">{{ $item->total }}</td>
                                    <td class="text-center">{{ $item->shipping }}</td>
                                    <td class="text-center">{{ $item->coupon_discount }}</td>
                                    <td class="text-center">{{ $item->gift_wrap }}</td>
                                    <td class="text-center">@money($total)</td>
                                    <td class="text-right">{{ $item->payment }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <thead>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-center">{{ count($items) }}</th>
                                    <th class="text-center">-</th>
                                    <th class="text-center" colspan="1">@money($shipping)</th>
                                    <th class="text-center">@money($cd)</th>
                                    <th class="text-center">@money($gift)</th>
                                    <th class="text-center" colspan="2">@money($gt)</th>
                                </tr>
                                <tr><th colspan="6" class="text-right">COD</th><th colspan="2">@money($cod)</th></tr>
                                <tr><th colspan="6" class="text-right">COD FULL</th><th colspan="2">@money($full_cod)</th></tr>
                                <tr><th colspan="6" class="text-right">SSL</th><th colspan="2">@money($sslcommerz)</th></tr>
                                <tr><th colspan="6" class="text-right">BKASH</th><th colspan="2">@money($bkash)</th></tr>
                                <tr><th colspan="6" class="text-right">NAGAD</th><th colspan="2">@money($nagad)</th></tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script>
    $('#date').daterangepicker({
        showWeekNumbers: true,
        showDropdowns: true,
        buttonClasses: 'btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
    });

    function printa4(){
        $("#print-data").print({
            addGlobalStyles : true,
            stylesheet : "{{ asset('assets/admin/css/print.css') }}",
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
    }

    function html_table_to_excel(type)
    {
        var data1 = document.getElementById('sales-data');
        var ws1 = XLSX.utils.table_to_sheet(data1);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws1, "Sales");
        XLSX.writeFile(wb, "report." + type);
    }

   /* function vendor_template(data) {
        if (data.loading) {return data.text;}
        var markup = '' + 
        "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'>" + data.name + "</div>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>Mobile: " + data.mobile + "</div>"+
            "</div>" + 
        "</div>";
      return markup;
    }

    function formatResult (data) {
        return data.name || data.text;
    }

    var user_select = $("#vendor_id").select2({
        theme: 'bootstrap',
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('/vendor/select') }}',
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) { return markup; },
        templateResult: vendor_template,
        templateSelection: formatResult
    });

    function select2_search($el, term) {
        $el.select2('open');
        var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
        $search.val(term);
        $search.trigger('keyup');
        setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
    }
    @if(request()->vendor_id)
    select2_search(user_select, 'id:{{ request()->vendor_id }}');
    @endif*/
</script>
@endpush