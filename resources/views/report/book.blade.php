{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Book Wise Report')
@section('content')
@php
// $product = ['All'=>'All','Book'=>'Book','Product'=>'Product'];
// $payment = ['All'=>'All','cod-full'=>'COD', 'cod'=>'COD Partial' ,'sslcommerz'=>'SSLCommerz', 'bkash'=>'bKash', 'nagad'=>'Nagad'];
// $cod = $sslcommer = $gt = $shipping = $cd = $gift = $full_cod = $bkash = $nagad = 0; 
$empty = [];
$status= ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$group= ['book'=>"Book",'author'=>'Author', 'publisher'=>'Publication'];
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
                        <x-form::select column="2" name="type" title="Group By" :required="true" type="text" value="" :options="$group" value="{{ request()->get('type', 'book' )}}" />
                        {{-- <x-form::select column="2" name="vendor_id" title="Vendor" :required="false" type="text" value="" :options="$empty" value="{{ request()->get('vendor_id', 'All' )}}"/> --}}
                        {{-- <x-form::select column="2" name="payment" title="Payment Method" :required="false" type="text" value="" :options="$payment" value="{{ request()->get('payment', 'All' )}}"/> --}}
                        {{-- <x-form::select column="2" name="status" title="Status" :required="false" type="text" value="" :options="$status" value="{{ request()->get('status', 4 )}}"/> --}}
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
                        @if($type=='book')
                        <table class="table table-bordered" id="books-data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book Name</th>
                                    <th>Author Name</th>
                                    <th>Publisher</th>
                                    <th>Quantity</th>
                                    <th>Base Rate</th>
                                    <th>Sale Rate</th>
                                    <th>Purchase Rate<sup>*</sup></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->book_name }}</td>
                                    <td>{{ $item->author_name }}</td>
                                    <td>{{ $item->publisher_name }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->rate }}</td>
                                    <td>{{ $item->sale }}</td>
                                    <td>{{ $item->buy }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        @if($type=='author')
                        <table class="table table-bordered" id="books-data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->author_name }}</td>
                                    <td>{{ $item->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        @if($type=='publisher')
                        <table class="table table-bordered" id="books-data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Publisher Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->publisher_name }}</td>
                                    <td>{{ $item->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')

@push('scripts')
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
    $('#date').daterangepicker({
        showWeekNumbers: true,
        showDropdowns: true,
        buttonClasses: 'btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
    });

    function printa4(){
        window.dt.destroy();
        $("#print-data").print({
            addGlobalStyles : true,
            stylesheet : "{{ asset('assets/admin/css/print.css') }}",
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
        setTimeout(function() {
            window.dt = $("#table-data").DataTable({iDisplayLength: -1});
        },2000);
    }

    window.dt = $("#books-data").DataTable({iDisplayLength: -1});

    function html_table_to_excel(type)
    {
        var data1 = document.getElementById('books-data');
        var ws1 = XLSX.utils.table_to_sheet(data1);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws1, "Book_Wise_Sales");
        XLSX.writeFile(wb, "report." + type);
    }
</script>
@endpush