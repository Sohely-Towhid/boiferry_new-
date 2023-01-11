{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title',@$type.' Review')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">{{ @$type }} Review</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">List of all {{ @$type }} Review</span>
                </h3>
                <div class="card-toolbar">
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
    window.review_status = function(val){
        var data = {"0": "Draft", "1": "Pending", "2": "Published", '3': 'Rejected'};
        var color = {"0": "warning", "1": "info", "2": "success",'3': 'danger'};
        return '<span class="badge badge-md badge-inline badge-' + color[val] + '">' + data[val] + '</span>';
    }
</script>
{!! $html->scripts() !!}
@endpush