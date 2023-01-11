{{-- BTL Template - Do not delete --}}
@php
$status = ['0'=>"Draft", '1'=> 'Active'];
$type = ['book'=>"Book"]; // 'product'=> 'Product', 
$empty = [];
$dtype = ['1'=> '৳ TK', '2'=>'% Percentage'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <div class="col-md-12">
        <div class="alert alert-info mb-4">
            <strong>কুপন রুলসঃ </strong><br>
            ১। মিন শপিং (সর্বনিম্ন কত টাকার শিপিং করলে ডুকাউন্ট চালু হবে) যদি চালু করা হয় তবে "কোন ভেন্ডর, প্রোডাক্ট, বুক আইডি" দেওয়া যাবে না;<br>
            ২। ডিকাউন্ট টাইপ পারসেন্টেজ দেওয়ার সময় ভালো করে খেয়াল করে দিতে হবে;<br>
            ৩। কুপন বানানোর পরে সেটা মাস্ট চেক করে দেখতে হবে;
        </div>
    </div>

    <x-form::select column="4" name="coupon_type" title="Coupon Type" :required="true" type="text" value="{{ (@$item->coupon_type) ? @$item->coupon_type : 'book' }}" :options="$type" />

    <x-form::select column="3" select2="true" multiple="true" name="product_id[]" title="Products" :required="false" type="text" value="" :options="$empty" />
    <x-form::select column="3" select2="true" multiple="true" name="book_id[]" title="Books" :required="false" type="text" value="" :options="$empty" />
    <x-form::select column="2" name="user_id" title="User ID" :required="false" type="text" value="{{ @$item->user_id }}" :options="$empty" />
    <x-form::select column="3" select2="true" multiple="true" name="author_id[]" title="Authors" :required="false" type="text" value="" :options="$empty" />
    <x-form::select column="3" select2="true" multiple="true" name="vendor_id[]" title="Vendors" :required="false" type="text" value="" :options="$empty" />
    <x-form::select column="3" select2="true" multiple="true" name="publisher_id[]" title="Publishers" :required="false" type="text" value="" :options="$empty" />
    {{-- <x-form::select column="4" name="category_id" title="category_id" :required="false" type="text" value="{{ @$item->category_id }}" :options="$empty" /> --}}
    
    <x-form::input column="3" name="code" title="Code" :required="true" type="text" value="{{ @$item->code }}" />
    <x-form::select column="3" name="type" title="Discount Type" :required="true" type="text" value="{{ @$item->type }}" :options="$dtype" />
    <x-form::input column="4" name="amount" title="Amount" :required="true" type="text" value="{{ @$item->amount }}" />
    <x-form::input column="4" name="min_shopping" title="Minimum Shopping" :required="false" type="text" value="{{ @$item->min_shopping }}" />
    {{-- Amount Type --}}
    
    {{-- <x-form::input column="4" name="min" title="Minimum Product" :required="false" type="text" value="{{ @$item->min }}" /> --}}
    {{-- <x-form::input column="4" name="max_use" title="Maximum Use" :required="false" type="text" value="{{ @$item->max_use }}" /> --}}
    <x-form::input column="4" name="start" title="Start Date" :required="true" type="text" value="{{ @$item->start }}" />
    <x-form::input column="4" name="expire" title="End Date" :required="true" type="text" value="{{ @$item->expire }}" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
<script>
	function hideAll(){
		$("#col_product_id, #col_book_id, #col_user_id, #col_vendor_id, #col_category_id, #col_min_shopping, #col_min, #col_max_use").hide();
	}
	
	$("#coupon_type").on('change', function(){
		hideAll();
		if($(this).val()=='book'){
			$("#col_book_id, #col_user_id, #col_vendor_id, #col_min, #col_max_use, #col_min_shopping").show();
		}
		if($(this).val()=='product'){
			$("#col_product_id, #col_user_id, #col_vendor_id, #col_min, #col_max_use").show();
		}
		if($(this).val()=='total_amount'){
			$("#col_min_shopping, #col_max_use").show();
		}
	});

    @if(!@$item->coupon_type)
    $("#coupon_type").val('book').change();
    @endif

	$('#start, #expire').daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"autoApply": true,
		"linkedCalendars": false,
		"showCustomRangeLabel": false,
        locale: {
            format: 'YYYY-MM-DD'
        }
	});

	var user_select = $("#user_id").select2({
        placeholder: "Select users",
		theme: 'bootstrap',
		ajax: {
		delay: 200,minimumInputLength: 2,
		url: '{{ url('/user/select') }}',
			processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
				var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
				return {results: result};
			},cache: false
		}
	});

	var product_select = $("#product_id").select2({
		theme: 'bootstrap',
        placeholder: "Select products",
		ajax: {
		delay: 200,minimumInputLength: 2,
		url: '{{ url('/product/select') }}',
			processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
				var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
				return {results: result};
			},cache: false
		}
	});

	var book_select = $("#book_id").select2({
		theme: 'bootstrap',
        placeholder: "Select books",
		ajax: {
		delay: 200,minimumInputLength: 2,
		url: '{{ url('/book/select') }}',
			processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
				var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
				return {results: result};
			},cache: false
		}
	});

	var vendor_select = $("#vendor_id").select2({
		theme: 'bootstrap',
        placeholder: "Select vendors",
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

    var author_select = $("#author_id").select2({
        theme: 'bootstrap',
        placeholder: "Select authors",
        ajax: {
        delay: 200,minimumInputLength: 2,
        url: '{{ url('/author/select?id=yes') }}',
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
        theme: 'bootstrap',
        placeholder: "Select publishers",
        ajax: {
        delay: 200,minimumInputLength: 2,
        url: '{{ url('/publication/select') }}',
            processResults: function(data) {
                if(data.results.length==1){
                    setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
                }
                var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
                return {results: result};
            },cache: false
        }
    });

	function select2_search($el, term, prefix) {
        if(!prefix){ prefix = 'id'; }
        if(Array.isArray(term)){
            $.each(term, function(i,v){
                setTimeout(function(){
                    select2_search($el, prefix + ':' + v);
                }, i * 1000);
            });
        }else{
            $el.select2('open');
            var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
            $search.val(term);
            $search.trigger('keyup');
            // setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
        }
    }

	@if(@$item->user_id)select2_search(user_select, '{{ "id:".@$item->user_id }}');@endif
	@if(@$item->vendor_id)select2_search(vendor_select, {!! @json_encode(@$item->vendor_id) !!});@endif
	@if(@$item->book_id)select2_search(book_select, {!! @json_encode(@$item->book_id) !!});@endif
	@if(@$item->product_id)select2_search(product_select, {!! @json_encode(@$item->product_id) !!});@endif
    @if(@$item->author_id)select2_search(author_select, {!! @json_encode(@$item->author_id) !!});@endif
    @if(@$item->publisher_id)select2_search(publisher_select, {!! @json_encode(@$item->publisher_id) !!});@endif

	hideAll();
</script>
@endpush
