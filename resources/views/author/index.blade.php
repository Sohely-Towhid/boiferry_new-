{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','All Author')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">All Author</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">List of All Author</span>
                </h3>
                <div class="card-toolbar">
                    <a href="javascript:{};" onclick="showMerge();" class="btn btn-fixed-height btn-warning font-weight-bolder font-size-sm px-5 my-1 mr-2"><i class="fas fa-sync"></i> Author Merge</a>
                    <a href="{{ url('author/create') }}" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                {!! $html->table(['class' => 'table table-bordered']) !!}
            </div>
        </div>
    </div>
</div>

<form action="{{ url('author/merge') }}" method="POST">
    @csrf
    <input type="hidden" name="ids" id="ids">
    <div class="modal fade" id="modal-merge" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Author Merge (<span id="a_t">0</span>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <x-form::select column="12" name="author_id" title="Merge With Author" :required="true" value="" :options="[]"/>
                    </div>      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Merge</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    window.author_status = function(val){
        var data = {"0": "Pending", "1": "Active", "2": "Banned"};
        return data[val];
    }

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
        dropdownParent: "#modal-merge",
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

    function showMerge(){
        var ids = [];
        $(".a_id").each(function(){
            if($(this).is(':checked')){
               ids.push($(this).val()); 
            }
        });
        console.log(ids);
        $("#ids").val(ids);
        if(ids.length){
            $("#a_t").html(ids.length);
            $("#modal-merge").modal('show');
        }
    }
</script>
{!! $html->scripts() !!}
@endpush