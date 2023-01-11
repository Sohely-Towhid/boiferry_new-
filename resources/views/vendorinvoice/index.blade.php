{{-- BTL Template - Do not delete --}}
@extends('layouts.seller')
@section('title','All nvoice')
@section('content')
@php
$status = ['-','System Pending','Pending','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$status_color = ['-','dark','info','info', 'success', 'danger','warning','info'];
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">All {{ $type }} Invoice</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">All {{ $type }} Invoice or Order</span>
                </h3>
                <div class="card-toolbar">
                    <form action="" method="GET">
                        <input type="hidden" name="status" value="{{ request()->status }}">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" placeholder="Search for..."/>
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Order Date</th>
                            <th>Last Update</th>
                            <th class="text-center">Pint</th>
                            <th class="text-center">COD</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th width="280px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <thead>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td class="text-center">{{ ($item->print) ? 'Yes' : 'No' }}</td>
                            <td class="text-center">{{ ($item->cod) ? 'Yes' : 'No' }}</td>
                            <td class="text-center">{{ $item->total }}</td>
                            <td class="text-center"><span class="badge badge-{{ $status_color[$item->status] }}">{{ $status[$item->status] }}</span></td>
                            <td class="text-center">
                                <div class="btn-group mr-2" role="group" aria-label="...">
                                    <a href="{{ url('invoice', $item->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i> Show</a>
                                    <button type="button" class="btn btn-primary" onclick="openPoP({{ $item->id }});"><i class="fa fa-print"></i> Print</button>
                                    <div class="btn-group" role="group">
                                        <button id="bt_g_{{ $item->id }}" type="button" class="btn btn-primary font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                        <div class="dropdown-menu" aria-labelledby="bt_g_{{ $item->id }}">
                                            <a class="dropdown-item" href="{{ url('invoice', $item->id) }}?packed=yes">Mark as Packed</a>
                                            <a class="dropdown-item" href="{{ url('invoice', $item->id) }}?cancel=yes">Cancel Order</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </thead>
                    @if($items->total()==0)
                    <tfoot>
                        <tr>
                            <td class="text-center" colspan="100%">Nothing found!</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    function openPoP(id){
        var thePopup = window.open('{{ url('invoice') }}/' + id + '?inv_print=all', "Invoice Print", "menubar=0,location=0,height=700,width=700" );
    }
</script>
@endpush