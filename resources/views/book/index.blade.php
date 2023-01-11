{{-- BTL Template - Do not delete --}}
@extends('layouts.'.$layout)
@section('title', $type.' Book')
@section('content')
@php $empty = []; @endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">{{ $type }} Book</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">List of {{ $type }} Books</span>
                </h3>
                <div class="card-toolbar">
                    <a href="javascript:{};" onclick="$('#filter').toggle();" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 mr-2 ml-3"><i class="fa fa-filter"></i></a>
                    <a href="{{ url('book/create') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                @if($layout=='admin')
                <form action="" method="get" id="filter" style="display:none;">
                    <input type="hidden" name="type" value="{{ request()->type }}">
                    <div class="row">
                        <x-form::select column="3" name="vendor" title="Seller" :required="true" type="text" value="" :options="[]" />
                        <x-form::select column="3" name="publisher" title="Publisher" :required="false" type="text" value="" :options="$empty" />
                        <x-form::select column="3" name="author" title="Author" :required="false" type="text" value="" :options="$empty" />
                        <div class="col">
                            <button type="submit" class="btn btn-info mt-8">Search</button>
                        </div>
                    </div>
                </form>
                @endif
                {!! $html->table(['class' => 'table table-bordered']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.book_status = function(val){
        var data = {"0": "Pending", "1": "Published", "2": "Removed"};
        var color = {"0": "warning", "1": "success", "2": "danger"};
        return '<span class="badge badge-md badge-inline badge-' + color[val] + '">' + data[val] + '</span>';
    }
    @if($layout=='admin')
    var publisher_select = $("#publisher").select2({
        theme: "bootstrap",
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('publication/select') }}',
            processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
                return {
                    results: data.results
                };
            },
            cache: false
        }
    });

    var author_select = $("#author").select2({
        theme: "bootstrap",
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('author/select?id=yes') }}',
            processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
                return {
                    results: data.results
                };
            },
            cache: false
        }
    });

    var vendor_select = $("#vendor").select2({
        theme: 'bootstrap',
        ajax: {
        delay: 200,minimumInputLength: 2,
        url: '{{ url('/vendor/select') }}',
            processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
                var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
                return {results: result};
            },cache: false
        }
    });

    @endif
</script>
{!! $html->scripts() !!}
@endpush