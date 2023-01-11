@extends('layouts.admin')

@section('content')
<div class="content d-flex flex-column flex-column-fluid">

    <div class="subheader py-6 py-lg-8 subheader-transparent">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
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

                <div class="col-md-12 mb-8">
                    <div class="card">
                        <div class="card-header">
                            Total Report
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center flex-wrap">
                                @foreach($total as $t_key => $t)
                                <div class="col-md-2 col-6 d-flex align-items-center flex-lg-fill mb-5 mb-lg-0">
                                    <span class="mr-4">
                                        <span class="fa fa-2x {{ $t['icon'] }}"></span>
                                    </span>
                                    <div class="d-flex flex-column text-dark-75">
                                        <span class="font-weight-bolder font-size-sm">{{ $t['title'] }}</span>
                                        <span class="font-weight-bolder font-size-h5">
                                            <span class="text-dark-50 font-weight-bold">{{ $t['value'] }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-8">
                    <div class="card">
                        <div class="card-header">
                            Today's Report
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center flex-wrap">
                                @foreach($today as $t_key => $t)
                                <div class="col-md-2 col-6 d-flex align-items-center flex-lg-fill mb-5 mb-lg-0">
                                    <span class="mr-4">
                                        <span class="fa fa-2x {{ $t['icon'] }}"></span>
                                    </span>
                                    <div class="d-flex flex-column text-dark-75">
                                        <span class="font-weight-bolder font-size-sm">{{ $t['title'] }}</span>
                                        <span class="font-weight-bolder font-size-h5">
                                            <span class="text-dark-50 font-weight-bold">{{ $t['value'] }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-8">
                    <div class="card">
                        <div class="card-header">
                            Yesterday's Report
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center flex-wrap">
                                @foreach($yesterday as $t_key => $t)
                                <div class="col-md-2 col-6 d-flex align-items-center flex-lg-fill mb-5 mb-lg-0">
                                    <span class="mr-4">
                                        <span class="fa fa-2x {{ $t['icon'] }}"></span>
                                    </span>
                                    <div class="d-flex flex-column text-dark-75">
                                        <span class="font-weight-bolder font-size-sm">{{ $t['title'] }}</span>
                                        <span class="font-weight-bolder font-size-h5">
                                            <span class="text-dark-50 font-weight-bold">{{ $t['value'] }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($blocks as $block_key => $block)
                <div class="col-xl-4">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder font-size-h4 text-dark-75">{{ @$block['title'] }}</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-lg">{{ @$block['sub_title'] }}</span>
                            </h3>
                        </div>
                        <div class="card-body pt-5">
                            @foreach($block['list'] as $list_key => $list)
                            <div class="bg-gray-100 d-flex align-items-center p-5 rounded gutter-b">
                                <div class="d-flex flex-center position-relative ml-4 mr-6 ml-lg-6 mr-lg-10">
                                    <i class="fa fa-users fa-2x"></i>
                                </div>
                                <div class="ml-1">
                                    <h3 class="text-dark-75 font-weight-bolder font-size-lg">{{ $list_key }}</h3>
                                    <p class="m-0 text-dark-50 font-weight-bold">Number of {{ $list_key }} {{ ucfirst( $block_key) }} is <strong class="badge badge-info">{{ $list }}</strong></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </div>
    

</div>
@endsection
