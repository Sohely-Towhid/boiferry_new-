@extends('layouts.admin')

@section('content')
@php

@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Marketing Data</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Marketing Data Export</span>
                </h3>
                <div class="card-toolbar">
                    <a href="?type=export" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1">Export</a>
                </div>
            </div>

            <div class="card-body pt-6">
                 @include('msg')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>DoB</th>
                            <th>Gender</th>
                            <th>Area</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->dob }}</td>
                            <td>{{ $item->gender }}</td>
                            <td>{{ $item->area }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    {!! $items->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
