{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title', 'Requisition List')
@section('content')
@php $empty = []; @endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-1 pt-6 pb-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Requisition List</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Requisition List</span>
                </h3>
                <div class="card-toolbar">
                    <a href="javascript:{};" onclick="printa4();" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 mr-2"><i class="fa fa-print"></i> Print</a>
                    <a href="javascript:{};" onclick="html_table_to_excel('xlsx');" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 mr-2">Excel</a>
                </div>
            </div>

            <div class="card-body pt-6" id="print-data">
                @include('msg')
                <div class="text-center">
                    <h2>Boiferry Requisition List</h2>
                    <h2>{{ date('Y-m-d') }}</h2>
                </div>
                <table class="table table-bordered table-black" id="books-data">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Publisher</th>
                            <th>Quantity</th>
                            <th>Shelf</th>
                            <th>MRP</th>
                            <th>Buy</th>
                            <th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->book->title_bn }}<br>{{ $item->book->title }}</td>
                            <td>{{ $item->book->author_bn }}<br>{{ $item->book->author }}</td>
                            <td>{{ $item->book->publication->name_bn }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->book->shelf }}</td>
                            <td>{{ $item->book->rate }}</td>
                            <td>{{ $item->book->buy }}</td>
                            <td class="no-print"><a href="javascript:{};" onclick="addStock({{ $item->id }},{{ $item->quantity }},'{{ $item->book->shelf }}');">add stock</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="" method="POST">
    @csrf
    <div class="modal fade" id="modal-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id" required>
                        <x-form::input column="12" name="qnt" title="Stock" :required="true" type="number" value="" />
                        <x-form::input column="12" name="buy" title="Buy Rate" :required="true" type="number" value="" />
                        <x-form::input column="12" name="shelf" title="Shelf" :required="true" type="text" value="" />
                    </div>      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script type="text/JavaScript" src="{{ asset('assets/admin/js/jQuery.print.min.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script type="text/javascript">

    function addStock(id,qnt,shelf) {
        $("#id").val(id);
        $("#shelf").val(shelf);
        $("#qnt").val(qnt);
        $("#modal-stock").modal('show');
    }

    function printa4(){
        $("#print-data").print({
            addGlobalStyles : true,
            stylesheet : "{{ asset('assets/admin/css/print.css') }}",
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
    }

    function html_table_to_excel(type)
    {
        var data1 = document.getElementById('books-data');
        var ws1 = XLSX.utils.table_to_sheet(data1);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws1, "Requisition_List");
        XLSX.writeFile(wb, "RequisitionList." + type);
    }
</script>
@endpush