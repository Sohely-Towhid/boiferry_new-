{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','All Subscription')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">All {{ ucfirst(@$type) }} Subscription</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">List of all {{ @$type }} subscription</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ url('subscription/create') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>
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
    window.subscription_status = function(val){
        var data = {"0": "-", "1": "Draft", "2": "Active", "3" : 'Expired'};
        var color = {"0": "", "1": "warning", "2": "success", '3': 'danger'};
        return '<span class="badge badge-md badge-inline badge-' + color[val] + '">' + data[val] + '</span>';
    }
</script>
{!! $html->scripts() !!}
@endpush