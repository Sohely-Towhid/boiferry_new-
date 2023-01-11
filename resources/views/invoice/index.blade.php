{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title',$type.' Invoice')
@section('content')
@php
$status = ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$status_color = ['-','dark','info','info', 'success', 'danger','warning','info'];
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-1 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">{{ $type }} Invoice</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">{{ $type }} Invoice in the system</span>
                </h3>
                <div class="card-toolbar">
                    {{-- <a href="{{ url('admin/invoice/create') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a> --}}
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                {!! $html->table(['class' => 'table table-bordered']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.invoice_status = function(val){
        var data = {!! json_encode($status) !!};
        var data_color = {!! json_encode($status_color) !!};
        return '<span class="badge badge-' + data_color[val] + '">' +  data[val] + '</span>';
    }
    window.invoice_print = function(val){
        var data = {"0": "No", "1": "Yes", "2": "Packed"};
        var data_color = {"0": "dark", "1": "success", "2": "success"};
        return '<span class="badge badge-' + data_color[val] + '">' +  data[val] + '</span>';
    }
</script>
{!! $html->scripts() !!}
@endpush