{{-- BTL Template - Do not delete --}}
@php
// extra_code
$language = ['বাংলা' => 'বাংলা', 'বাংলা,English' => 'বাংলা,English', 'বাংলা,Arabic' => 'বাংলা,Arabic','English' => 'English','Arabic' => 'Arabic','Hibru' => 'Hibru','Sanskrit' => 'Sanskrit', 'Hindi' => 'Hindi', 'Chinese' => 'Chinese','Japanese' => 'Japanese'];
$empty = [];
$status = ['Pending','Active','Reject','N/A'];
$in_home = ['No','Yes'];
$type = ['hardcover'=>'হার্ডকাভার','paperback'=>'পেপারব্যাক','copy'=>'কপি'];
$book_type = ['unicode'=>"Unicode", 'ascii'=>'ASCII'];
@endphp
<style>
    .custom-file-uploader {
    position: relative;
}
</style>
<!-- form start -->

<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <div class="col-md-10">
        <div class="row">
            <x-form::select column="2" name="language" title="Language" :required="true" type="text" value="{{ @$item->language }}" :options="$language" />
            <x-form::input column="2" name="isbn" title="ISBN (Barcode on Book)" :required="false" type="text" value="{{ @$item->isbn }}" />
            <x-form::input column="4" name="title" title="Title" :required="true" type="text" value="{{ @$item->title }}" />
            <x-form::input column="4" name="title_bn" title="Title (Bangla)" :required="true" type="text" value="{{ @$item->title_bn }}" />
            <x-form::select column="3" name="type" title="Binding Type" :required="true" type="text" value="{{ @$item->type }}" :options="$type"/>
            <x-form::select column="3" name="author" title="Author" :required="true" type="text" value="{{ @$item->author }}" :options="$empty"/>
            <x-form::input column="3" name="author_bn" title="Author (Bangla)" :required="true" type="text" value="{{ @$item->author_bn }}" />
            <x-form::select column="3" name="publisher_id" title="Publisher" :required="true" type="text" value="{{ @$item->publisher_id }}" :options="$empty" />
            <x-form::select column="4" name="category_id" title="Category" :required="true" type="text" value="{{ @$item->category_id }}" :options="$empty" />
            <x-form::input column="2" name="published_at" title="First Print" :required="true" type="text" value="{{ @$item->published_at }}" />
            <x-form::input column="2" name="buy" title="Purchase" :required="false" type="text" value="{{ @$item->buy }}" />
            <x-form::input column="2" name="rate" step="0.01" title="Rate" :required="true" type="number" value="{{ @$item->rate }}" />
            <x-form::input column="2" name="sale" step="0.01" title="Sale" :required="true" type="text" value="{{ @$item->sale }}" />
            <x-form::input column="2" name="number_of_page" title="Number of Page" :required="true" type="number" value="{{ @$item->number_of_page }}" />
            <x-form::input column="2" name="stock" title="Stock" :required="true" type="number" value="{{ @$item->stock }}" />
            {{-- <x-form::input column="2" name="point" title="Store Point" :required="false" type="number" value="{{ @$item->point }}" /> --}}
            <x-form::file column="3" name="preview" :delete="true" title="Book Preview (PDF)" :required="false" type="file" value="{{ @$item->preview }}" />
        </div> 
    </div>
    <x-form::images column="2" name="images" extra="book=yes" title="Images" size="664 x 1000 px" :required="true" type="number" :value="@$item->images" />
    {{-- 664, 1000 --}}
    <x-form::textarea column="12" name="short_description" title="Short Description" :required="false" value="{{ @$item->short_description }}" />
    <x-form::textarea column="12" name="description" title="Book Summary" :required="true" value="{{ @$item->description }}" />
    <div class="col-md-12 mb-5">
        <h3>Ebook</h3>
        <div class="separator separator-dashed my-5"></div>
    </div>
    <x-form::file column="3" name="ebook" :delete="true" title="Ebook (ePub)" :required="false" type="file" value="{{ @$item->ebook }}" />
    <x-form::input column="3" name="ebook_rate" title="Ebook Rate" :required="false" type="number" value="{{ (float) @$item->ebook_rate }}" />
    <x-form::input column="3" name="ebook_sale" title="Ebook Sale" :required="false" type="number" value="{{ (float) @$item->ebook_sale }}" />
    <x-form::input column="3" name="free_page" title="Free Page (eBook)" :required="false" type="number" value="{{ (int) @$item->free_page }}" />
    <x-form::select column="3" name="subscription" title="Read via Subscription" :required="false" value="{{ (int) @$item->subscription }}" :options="$in_home" />
    <x-form::select column="3" name="ebook_type" title="eBook Type" :required="false" value="{{ @$item->ebook_type }}" :options="$book_type" />

    <div class="col-md-12 mb-5">
        <h3>Author / Translator (Optional) <button type="button" class="btn btn-success" onclick="addAuthor();"> + </button></h3>
        <div class="separator separator-dashed my-5"></div>
        <div id="authors"></div>
    </div>

    @if(@$layout=='admin')
    <x-form::select column="4" name="vendor_id" title="Seller" :required="true" type="text" value="{{ @$item->vendor_id }}" :options="[]" />
    <x-form::select column="2" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <x-form::select column="2" name="pre_order" title="Pre-Order" :required="true" type="text" value="{{ @$item->pre_order }}" :options="$in_home" />
    <x-form::input column="3" name="slug" title="Slug" :required="true" type="text" value="{{ @$item->slug }}" />
    <x-form::input column="2" name="actual_stock" title="Actual Stock" :required="true" type="number" value="{{ @$item->actual_stock }}" />
    <x-form::input column="2" name="shelf" title="Shelf (Storage Box)" :required="false" type="text" value="{{ @$item->shelf }}" />
    <x-form::select column="12" name="fbt[]" id="fbt" title="Frequently Bought Together" :required="false" type="text" value="null" :options="[]" />
    @include('seo')
    @endif
    <div class="col-md-12">
        @if(@$layout=='admin')
        <button type="button" onclick="autoSEO();" class="btn btn-info float-right">Auto SEO</button>
        @endif
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->


@push('scripts')
<script>
$('#published_at').daterangepicker({
    showWeekNumbers: true,
    singleDatePicker: true,
    showDropdowns: true,
    buttonClasses: 'btn',
    applyClass: 'btn-primary',
    cancelClass: 'btn-secondary',
    locale: {
      format: 'Y-MM-DD'
    }
});

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

var author_select = $("#author").select2({
    theme: "bootstrap",
    ajax: {
        delay: 200,
        minimumInputLength: 2,
        url: '{{ url('author/select') }}',
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


$('#author').on('select2:select', function (e) {
    var data = e.params.data;
    $("#author_bn").val(data.name_bn);
    console.log(data);
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

var category_select = $("#category_id").select2({
    theme: "bootstrap",
    ajax: {
        delay: 200,
        minimumInputLength: 2,
        url: '{{ url('book/category/select') }}',
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

var fbt_select = $("#fbt").select2({
    delay: 200,
    multiple: true,
    placeholder: "Select Books...",
    ajax: {
        data: function (term, page) {
            return {q: term.term, term: term.term};
        },
        url: '{{ url('book/select') }}',
        processResults: function(data) {
            var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
            return {
                results: result
            };
        },
        cache: false
    }
});

function select2_search($el, term) {
    $el.select2('open');
    var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
    $search.val(term);
    $search.trigger('keyup');
}

@if(isset($item))
select2_search(author_select, 'ex:{{ @$item->author }}');
select2_search(publisher_select, 'id:{{ @$item->publisher_id }}');
select2_search(category_select, 'id:{{ @$item->category_id }}');
select2_search(vendor_select, 'id:{{ @$item->vendor_id }}');
@if(!empty($item->fbt) && is_array($item->fbt))
select2_search(fbt_select, 'id:{{ implode(',', $item->fbt) }}');
@endif
@endif

function autoSEO(){
    var keywords = [
        $("#title").val(), 
        $("#title").val() +' in boiferry',
        $("#title").val() +' buy online',
        $("#title").val() + " by " + $("#author").val(),
        $("#title_bn").val(),
        $("#title_bn").val() + ' বইফেরীতে',
        $("#title_bn").val() + ' অনলাইনে কিনুন',
        $("#author_bn").val() + ' এর ' + $("#title_bn").val()
    ];
    if($("#isbn").val()){
        keywords.push($("#isbn").val());
    }
    keywords.push($("#title").val() + ' Ebook');
    keywords.push($("#title").val() + ' Ebook in BD');
    keywords.push($("#title").val() + ' Ebook in Dhaka');
    keywords.push($("#title").val() + ' Ebook in Bangladesh');
    keywords.push($("#title").val() + ' Ebook in boiferry');
    keywords.push($("#title_bn").val() + ' ইবুক');
    keywords.push($("#title_bn").val() + ' ইবুক বিডি');
    keywords.push($("#title_bn").val() + ' ইবুক ঢাকায়');
    keywords.push($("#title_bn").val() + ' ইবুক বাংলাদেশে');

    if($("#meta_description").val()==''){
        var meta = $("#author_bn").val() + ' এর ' + $("#title_bn").val() + ' এখন পাচ্ছেন বইফেরীতে মাত্র ' + $("#sale").val()  + ' টাকায়। এছাড়া বইটির ইবুক ভার্শন পড়তে পারবেন বইফেরীতে। ';
        meta += $("#title").val() + ' by ' + $("#author").val() + 'is now available in boiferry for only ' + $("#sale").val()  + ' TK. You can also read the e-book version of this book in boiferry.';
        $("#meta_description").val(meta);
    }
    $("#keywords").select2({
        data: keywords,
        tags: true,
        multiple: true,
        tokenSeparators: [',', '|']
    });
    $('#keywords').val(keywords).change();
}
window.author = 0;
function addAuthor(value,type){
    window.author += 1;
    var author_id = window.author;
    var html = '';
    html += '<div class="row mb-5">';
    html += '    <div class="col">';
    html += '        <div class="from-group">';
    html += '            <label for="author_type_' + author_id +'">Author Type</label>';
    html += '            <select type="text" name="author_type[]" id="author_type_' + author_id +'" class="form-control">';
    html += '                <option value="Author">Author</option>';
    html += '                <option value="Translator">Translator</option>';
    html += '                <option value="Editor">Editor</option>';
    html += '            </select>';
    html += '        </div>';
    html += '    </div>';
    html += '    <div class="col">';
    html += '        <div class="from-group">';
    html += '            <label for="author_name_' + author_id + '">Name in English</label>';
    html += '            <select type="text" name="author_name[]" id="author_name_' + author_id + '" class="form-control"></select>';
    html += '        </div>';
    html += '    </div>';
    html += '    <div class="col">';
    html += '        <div class="from-group">';
    html += '            <label for="author_name_bn_' + author_id + '">Name in Bangla</label>';
    html += '            <input type="text" name="author_name_bn[]" id="author_name_bn_' + author_id + '" class="form-control">';
    html += '        </div>';
    html += '    </div>';
    html += '</div>';

    $("#authors").append(html);
    var ot = [];
    ot[author_id] = $("#author_name_" + author_id).select2({
        theme: "bootstrap",
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('author/select') }}',
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

    $('#author_name_'  + author_id).on('select2:select', function (e) {
        var data = e.params.data;
        $("#author_name_bn_" + author_id).val(data.name_bn);
    });

    if(value){
        select2_search(ot[author_id], 'ex:' + value);
        $("#author_type_" + author_id).val(type).change();
    }
}


$("#sale").on('change keyup keydown', function(){
    var sale = $("#sale").val();
    var is_per = sale.match(/([0-9.]+)%/);
    if(is_per){
        var rate = Number($("#rate").val());
        var new_sale = rate * (Number(is_per[1]) / 100);
        $("#sale").val(new_sale.toFixed(2));
    }else{
        var num = sale.match(/([0-9\.]+)/);
        if(num){
            $("#sale").val(num[1]);
        }else{
            $("#sale").val('');
        }
    }
});

@if(@$item->others)
@foreach($item->others as $ot)
addAuthor('{{ $ot->name }}', '{{ $ot->type }}');
@endforeach
@endif

$("#buy").on('change keyup', function(){
    if($(this).val().match(/%/)){
        var rate = Number($("#rate").val());
        var dis = Number($(this).val().replace(/%/g, ''));
        var buy = rate - ((rate / 100) * dis);
        $(this).val(buy);
    }
});
</script>
@endpush

