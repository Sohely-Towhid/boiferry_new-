{{-- BTL Template - Do not delete --}}
@extends('layouts.'.$layout)
@section('title','Bulk Price Update')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">

        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Bulk Price Update</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Bulk Price Update for Books</span>
                </h3>
                <div class="card-toolbar">
                    <!--<a href="" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>-->
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                <form action="" method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    @php $empty = []; @endphp
                    <x-form::select column="3" name="author_id" title="Author" :required="false" type="text" :options="$empty"/>
                    <x-form::select column="3" name="publisher_id" title="Publisher" :required="false" type="text" :options="$empty"/>
                    <x-form::select column="3" name="vendor_id" title="Seller" :required="false" type="text" :options="$empty"/>
                    <x-form::input column="3" name="amount" title="Amount (%)" :required="true" type="number" value="0" step="0.01"/>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Update Bulk Price</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Bulk % Update</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Bulk % Update for Sale</span>
                </h3>
                <div class="card-toolbar">
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                <form action="" method="POST" enctype="multipart/form-data" class="row">
                    <input type="hidden" name="bulk_per" value="yes">
                    @csrf
                    @php $t = ['increment'=> '+ Increment (ডিস্কাউন্ট বাড়বে)', 'decrement'=> '+ Decrement (ডিস্কাউন্ট কমবে)']; @endphp
                    <x-form::select column="3" name="type" title="% Type" :required="false" type="text" :options="$t"/>
                    <x-form::input column="3" name="amount" title="Amount (%)" :required="true" type="number" value="0" step="0.01"/>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Update Bulk %</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function author_template(data) {
    if (data.loading) {return data.name;}
    var markup = '' + 
    "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" + data.name + "</div>" +
        "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + data.name_bn + "</div>"+
        "</div>" + 
    "</div>";
  return markup;
}

function authorFormatResult (data) {
    return data.name || data.name_bn;
}

var author_select = $("#author_id").select2({
    theme: "bootstrap",
    ajax: {
        delay: 200,
        minimumInputLength: 2,
        url: '{{ url('author/select?id=yes') }}',
        processResults: function(data) {
            if(data.results.length==1 && data.results[0].autoselect){
                setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
            }
            return {
                results: data.results
            };
        },
        cache: false
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: author_template,
    templateSelection: authorFormatResult
});

var vendor_select = $("#vendor_id").select2({
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

var publisher_select = $("#publisher_id").select2({
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
</script>
@endpush