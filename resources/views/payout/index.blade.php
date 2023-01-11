{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title',@$type.' Payout')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">{{ @$type }} Payout</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Seller Payout ({{ @$type }})</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ url('payout?sync=yes') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-sync"></i> Sync Now</a>
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
    window.payout_status = function(val){
        var data = {"0": "Draft", "1": "Unpaid", "2": "Paid","3" : 'S.R.'};
        var data_color = {"0": "dark", "1": "info", "2": "success","3": 'warning'};
        return '<span class="badge badge-' + data_color[val] + '">' +  data[val] + '</span>';
    }
</script>
{!! $html->scripts() !!}
@endpush