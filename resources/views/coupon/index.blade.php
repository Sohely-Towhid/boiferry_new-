{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','All Coupon')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-1 pt-6 pb-2">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">All Coupon</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">List of All Coupon</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ url('coupon/create') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>
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
    window.coupon_status = function(val){
        var data = {"0": "Pending", "1": "Active", "2": "Banned"};
        return data[val];
    }
</script>
{!! $html->scripts() !!}
@endpush