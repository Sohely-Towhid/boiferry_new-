{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Payout #'.$item->id)
@section('content')
@php
$user = Auth::user();
$status = ["Draft", "Unpaid", "Paid" ,'S.R.'];
$method = ["Bank"=>'Bank', "Cheque"=>'Cheque', "BFT"=>'BFT' ,'EFTN'=>'EFTN',"Bank Diposit"=>'Bank Diposit'];
@endphp
<style type="text/css">
    .h-100px{
        height: 100px;
    }
</style>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="row">
            <div class="col-md-8">
                @include('msg')
                @if($item->status==2)
                <div class="card card-custom bg-success mb-5">
                    <div class="card-body text-white" id="page_footer">
                        Paid via <strong>{{ $item->method }}</strong> approved by <strong>{{ $item->user->name }}</strong><br>
                        Details: {{ $item->details }}
                    </div>
                </div>
                @endif
                <div class="card card-custom">
                     <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Seller Payout# {{ $item->id }}</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-sm btn-success font-weight-bold" onclick="printa4();">
                                <i class="fa fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-6" id="payout_print">
                        <div class="row justify-content-center py-8 px-8 px-md-0">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                                    <h1 class="display-4 font-weight-boldest mb-10">PAYOUT#{{ $item->id }}<br>{{ strtoupper($status[$item->status]) }}</h1>
                                    <div class="d-flex flex-column align-items-md-end px-0">
                                        <!--begin::Logo-->
                                        <a href="#" class="mb-5">
                                            <img src="{{ asset('assets/images/payout-logo.png') }}" class="h-100px rounded" alt="">
                                        </a>
                                        <!--end::Logo-->
                                        <span class="d-flex flex-column align-items-md-end opacity-70">
                                            <span>3rd Floor, Abedin Bhaban, Soni Akra,</span>
                                            <span>Dhaka, 1236, Bangladesh</span>
                                        </span>
                                    </div>
                                </div>
                                <div id="inv_body">
                                    <div class="border-bottom w-100"></div>
                                    <table class="table no-border align-top table-print">
                                        <tr>
                                            <td width="33.33333%">
                                                <div class="font-weight-bolder mb-2">DATE</div>
                                                <div class="opacity-70">{{ $item->updated_at->format('M d, Y') }}</div>
                                            </td>
                                            <td width="33.33333%">
                                                <div class="font-weight-bolder mb-2">PAYOUT NO.</div>
                                                <div class="opacity-70">SP {{ $item->id }}</div>
                                            </td>
                                            <td width="33.33333%">
                                                <div class="font-weight-bolder mb-2">PAYOUT TO.</div>
                                                <div class="opacity-70">{!! str_replace("\n", '<br>', $item->vendor->address) !!} 
                                                <br>Bangladesh</div>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- <div class="d-flex justify-content-between pt-6">
                                        <div class="d-flex flex-column flex-root">
                                            <span class="font-weight-bolder mb-2">DATE</span>
                                            <span class="opacity-70">{{ $item->updated_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="d-flex flex-column flex-root">
                                            <span class="font-weight-bolder mb-2">PAYOUT NO.</span>
                                            <span class="opacity-70">SP {{ $item->id }}</span>
                                        </div>
                                        <div class="d-flex flex-column flex-root">
                                            <span class="font-weight-bolder mb-2">PAYOUT TO.</span>
                                            <span class="opacity-70">{!! str_replace("\n", '<br>', $item->vendor->address) !!} 
                                            <br>Bangladesh</span>
                                        </div>
                                    </div> --}}

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="pl-0 font-weight-bold text-muted text-uppercase">Description</th>
                                                    <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="font-weight-boldest">
                                                    <td class="pl-0 pt-7">Total Sales</td>
                                                    <td class="pr-0 pt-7 text-right">৳ @money($item->amount)</td>
                                                </tr>
                                                <tr class="font-weight-boldest border-bottom-0">
                                                    <td class="border-top-0 pl-0 py-4">Payment Fee</td>
                                                    <td class="text-danger border-top-0 pr-0 py-4 text-right">- ৳ @money($item->pg_fee)</td>
                                                </tr>
                                                <tr class="font-weight-boldest border-bottom-0">
                                                    <td class="border-top-0 pl-0 py-4">Winners Bazar Fee</td>
                                                    <td class="text-danger border-top-0 pr-0 py-4 text-right">- ৳ @money($item->fee)</td>
                                                </tr>
                                                <tr class="font-weight-boldest border-bottom-0">
                                                    <td class="border-top-0 pl-0 py-4">Total Refund</td>
                                                    <td class="text-danger border-top-0 pr-0 py-4 text-right">- ৳ @money($item->refund)</td>
                                                </tr>
                                                <tr class="font-weight-boldest border-bottom-0">
                                                    <td class="border-top-0 pl-0 py-4">Total Payable</td>
                                                    <td class="text-success border-top-0 pr-0 py-4 text-right">৳ @money($item->amount - $item->fee - $item->pg_fee - $item->refund)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if($item->status<2 && in_array($user->role, ['admin', 'accounts']))
                                <form action="{{ url('payout/'.$item->id) }}" method="POST">
                                    <div class="border-bottom w-100 mb-10"></div>
                                    @method('put')
                                    @csrf
                                    <div class="row">
                                        <x-form::select column="2" name="status" title="Status" :required="true" value="{{ @$item->status }}" :options="['Draft','Unpaid','Paid']" />
                                        <x-form::select column="3" name="method" title="Payment Method" :required="true" value="{{ @$item->method }}" :options="$method" />
                                        <x-form::textarea column="7" name="details" title="Payment Details" :required="true" type="text" value="{{ @$item->details }}" />
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success">Update Payout</button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title"><h3 class="card-label">Bank Details</h3></div>
                    </div>
                    <div class="card-boy py-8 px-8">
                        {!! str_replace("\n", '<br>', $item->vendor->bank) !!}
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div id="print-data" style="display: none;">
    <div id="inv-header">
        <table class="table no-border">
            <tr>
                <td width="30%"><h1 class="display-4 font-weight-boldest mb-10">PAYOUT#{{ $item->id }}<br>{{ strtoupper($status[$item->status]) }}</h1></td>
                <td class="text-right"><img src="{{ asset('assets/images/payout-logo.png') }}" class="h-100px rounded" alt=""><br>
                    <span class="d-flex flex-column align-items-md-end opacity-70 print-pt-5">
                        <span>3rd Floor, Abedin Bhaban, Soni Akra,</span>
                        <span>Dhaka, 1236, Bangladesh</span>
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div id="inv-body"></div>
    <div id="inv-footer" style="border: 1px dotted black; padding: 5mm;"></div>
</div>
@endsection

@push('scripts')
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script>
    function printa4(){
        // $("#inv-header tr td:nth-child(1)").html("XXXX");
        // $("#inv-header tr td:nth-child(2)").html("XXXX");
        $("#inv-body").html($("#inv_body").html());
        $("#inv-footer").html($("#page_footer").html());
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
    }
</script>
@endpush