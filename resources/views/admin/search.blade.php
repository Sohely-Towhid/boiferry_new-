@extends('layouts.admin')

@section('content')
@php
$status = ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$status_color = ['danger','dark','info','info', 'success', 'danger','warning','info'];
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="" method="GET">
                                    <div class="form-group d-flex">
                                        <input type="text" class="form-control" name="q" placeholder="Search Here" value="{{ request()->q }}">
                                        <button type="submit" class="btn btn-gradient-primary border ml-3">Search</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <h2>Search Result For<u class="ml-2">"{{ request()->q }}"</u></h2>
                                <p class="text-muted">About ~{{ count($customer) + count($invoices) + count($books) }} results ({{ round((microtime(true) - LARAVEL_START) * 1000) }} ms)</p>
                                <hr>
                            </div>
                            <div class="col-12 results">
                                @foreach($customer as $item)
                                <div class="pt-4 border-bottom">
                                    <a class="d-block h4 mb-1" href="#">{{ $item->name }} / {{ $item->mobile }} / {{ $item->email }}</a>
                                    <a class="page-url text-primary" href="{{ url('user/'.$item->id.'/access') }}">Customer Auth User</a>
                                    <p class="page-description mt-1 w-75 text-muted">
                                        <strong>Invoice: </strong>
                                        <div class="row">
                                            @foreach($item->invoices as $invoice)
                                            <div class="col-md-3 pb-2">
                                                <a href="{{ url('invoice/'.$invoice->id) }}">{{ $invoice->id }}</a> - {{ $invoice->updated_at->format('Y-m-d') }} - <span class="badge badge-inline badge-{{ $status_color[$invoice->status] }}">{{ $status[$invoice->status] }}</span>
                                            </div>
                                            @endforeach
                                        </div>   
                                    </p>
                                </div>
                                @endforeach

                                @foreach($invoices as $item)
                                <div class="pt-4 border-bottom">
                                    <a class="d-block h4 mb-1" href="#">INV#{{ $item->id }} / {{ ean8(1000000 + $item->id) }} / {{ $item->user->name }} / {{ $item->user->mobile }} / {{ $item->user->email }}</a>
                                    <span class="badge badge-inline badge-{{ $status_color[$item->status] }}">{{ $status[$item->status] }}</span> 
                                    <a class="btn btn-sm text-primary" href="{{ url('invoice/'.$item->id) }}">View Invoice</a>
                                    <a class="btn btn-sm text-primary" href="sip://{{ $item->user->mobile }}">Call</a>
                                    <a class="btn btn-sm text-primary" href="mailto://{{ $item->user->email }}">Email</a>
                                    <a class="btn btn-sm text-primary" href="{{ url('admin/search') }}?q={{ $item->user->mobile }}">All Invoice</a>
                                    <p class="page-description mt-1 w-75 text-muted">
                                        Note: {{ $item->note }} / Tracking: {{ $item->tracking }} / Referral: {{ $item->referral}}
                                    </p>
                                </div>
                                @endforeach

                                @foreach($books as $item)
                                @if($item->invoice->user_id && $item->invoice->status>0)
                                <div class="pt-4 border-bottom">
                                    <a class="d-block h4 mb-1" href="#">INV#{{ $item->invoice_id }} / {{ ean8(1000000 + $item->invoice_id) }} / {{ $item->invoice->user->name }} / {{ $item->invoice->user->mobile }} / {{ $item->invoice->user->email }}</a>
                                    <span class="badge badge-inline badge-{{ $status_color[$item->invoice->status] }}">{{ $status[$item->invoice->status] }}</span> 
                                    <a class="btn btn-sm text-primary" href="{{ url('invoice/'.$item->invoice_id) }}">View Invoice</a>
                                    <a class="btn btn-sm text-primary" href="sip://{{ $item->invoice->user->mobile }}">Call</a>
                                    <a class="btn btn-sm text-primary" href="mailto://{{ $item->invoice->user->email }}">Email</a>
                                    <a class="btn btn-sm text-primary" href="{{ url('admin/search') }}?q={{ $item->invoice->user->mobile }}">All Invoice</a>
                                    <p class="page-description mt-1 w-75 text-muted">
                                        <strong>Book: {{ $item->product->title }} - {{ $item->product->title_bn }}</strong><br>
                                        Note: {{ $item->invoice->note }} / Tracking: {{ $item->invoice->tracking }} / Referral: {{ $item->invoice->referral}}
                                    </p>
                                </div>
                                @endif
                                @endforeach

                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
