{{-- BTL Template - Do not delete --}}
@php
$status = ['Pending','Active','Reject','Banned'];
$yn = ['No',"Yes"];
$empty = null;
$banks = ['Sonali Bank Limited' => 'Sonali Bank Limited','Janata Bank Limited' => 'Janata Bank Limited','Agrani Bank Limited' => 'Agrani Bank Limited','Rupali Bank Limited' => 'Rupali Bank Limited','BASIC Bank Limited' => 'BASIC Bank Limited','Bangladesh Development Bank' => 'Bangladesh Development Bank','AB Bank Limited' => 'AB Bank Limited','Bangladesh Commerce Bank Limited' => 'Bangladesh Commerce Bank Limited','Bank Asia Limited' => 'Bank Asia Limited','BRAC Bank Limited' => 'BRAC Bank Limited','Citizens Bank PLC' => 'Citizens Bank PLC','City Bank Limited' => 'City Bank Limited','Community Bank Bangladesh Limited' => 'Community Bank Bangladesh Limited','Dhaka Bank Limited' => 'Dhaka Bank Limited','Dutch-Bangla Bank Limited' => 'Dutch-Bangla Bank Limited','Eastern Bank Limited' => 'Eastern Bank Limited','IFIC Bank Limited' => 'IFIC Bank Limited','Jamuna Bank Limited' => 'Jamuna Bank Limited','Meghna Bank Limited' => 'Meghna Bank Limited','Mercantile Bank Limited' => 'Mercantile Bank Limited','Midland Bank Limited' => 'Midland Bank Limited','Modhumoti Bank Limited' => 'Modhumoti Bank Limited','Mutual Trust Bank Limited' => 'Mutual Trust Bank Limited','National Bank Limited' => 'National Bank Limited','National Credit & Commerce Bank Limited' => 'National Credit & Commerce Bank Limited','NRB Bank Limited' => 'NRB Bank Limited','NRB Commercial Bank Ltd' => 'NRB Commercial Bank Ltd','One Bank Limited' => 'One Bank Limited','Padma Bank Limited' => 'Padma Bank Limited','Premier Bank Limited' => 'Premier Bank Limited','Prime Bank Limited' => 'Prime Bank Limited','Pubali Bank Limited' => 'Pubali Bank Limited','Shimanto Bank Ltd' => 'Shimanto Bank Ltd','Southeast Bank Limited' => 'Southeast Bank Limited','South Bangla Agriculture and Commerce Bank Limited' => 'South Bangla Agriculture and Commerce Bank Limited','Trust Bank Limited' => 'Trust Bank Limited','United Commercial Bank Ltd' => 'United Commercial Bank Ltd','Uttara Bank Limited' => 'Uttara Bank Limited','Bengal Commercial Bank Ltd' => 'Bengal Commercial Bank Ltd'];

@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::select column="4" name="user_id" title="User ID" :required="true" type="text" value="{{ @$item->user_id }}" :options="$empty" />
    <x-form::input column="4" name="name" title="Shop Name" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::input column="4" name="slug" title="Shop Slug" :required="false" type="text" value="{{ @$item->slug }}" />
    <x-form::input column="4" name="mobile" title="Mobile" :required="true" type="text" value="{{ @$item->mobile }}" />
    <x-form::input column="4" name="email" title="Email" :required="true" type="text" value="{{ @$item->email }}" />
    <x-form::input column="4" name="address" title="Address" :required="true" type="text" value="{{ @$item->address }}" />
    {{-- <x-form::input column="4" name="logos" title="Logo" :required="false" type="text" value="{{ @$item->logos }}" /> --}}
    {{-- <x-form::input column="4" name="banners" title="Banners" :required="false" type="text" value="{{ @$item->banners }}" /> --}}
    {{-- <x-form::select column="12" name="category" title="Category" :required="false" type="text" value="" :options="$empty" /> --}}
    <div class="col-md-12" id="col_category">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control" multiple></select>
        </div>
    </div>
    {{-- <x-form::input column="4" name="details" title="Details" :required="false" type="text" value="{{ @$item->details }}" /> --}}
    {{-- <x-form::input column="4" name="files" title="Files" :required="true" type="text" value="{{ @$item->files }}" /> --}}
    <x-form::input column="4" name="fee" title="Fee" :required="true" type="text" value="{{ @$item->fee }}" />
    <x-form::select column="4" name="book" title="Seel Book" :required="false" type="text" value="{{ @$item->book }}" :options="$yn" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />

    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <h2>Bank Details</h2>
                <div class="border-bottom mb-5"></div>
            </div>
            <x-form::select column="4" name="bank[bank]" title="Bank Name" :required="false" type="text" value="{{ @$item->details['bank']['bank'] }}" :options="$banks" />
            <x-form::input column="4" name="bank[name]" title="Account Name" :required="false" type="text" value="{{ @$item->details['bank']['name'] }}" />
            <x-form::input column="4" name="bank[number]" title="Account Number" :required="false" type="text" value="{{ @$item->details['bank']['number'] }}" />
            <x-form::input column="4" name="bank[branch]" title="Branch Name" :required="false" type="text" value="{{ @$item->details['bank']['branch'] }}" />
            <x-form::input column="4" name="bank[routing]" title="Routing Number" :required="false" type="text" value="{{ @$item->details['bank']['routing'] }}" />
            <x-form::select column="4" name="bank[verified]" title="Verified" :required="false" type="text" value="{{ @$item->details['bank']['verified'] }}" :options="$yn" />
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <h2>Other Details</h2>
                <div class="border-bottom mb-5"></div>
            </div>
            <x-form::textarea column="12" name="details[other]" title="Other Details" :required="false" type="text" value="{{ @$item->details['other'] }}" />
        </div>
    </div>    

    <x-form::input column="12" name="reason" title="Reject Reason" :required="true" type="text" value="Lack of Documents" />
    @if(@$item->files)
    <div class="col-md-12">
    	<hr>
        @foreach(@$item->files as $key => $file)
        <a href="{{ url('seller/'.$item->id.'/download?file='.$file) }}" class="btn btn-info mr-2">Download {{ ucwords(str_replace(["_",'-'],' ',$key)) }}</a>
        @endforeach
        <hr>
    </div>
    @endif

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
<script>
    $("#col_reason").hide();
    $("#status").on('change', function(){
        if($(this).val()==2){
            $("#col_reason").show();
        }else{
            $("#col_reason").hide();
        }
    });
	var user_select = $("#user_id").select2({
		theme: 'bootstrap',
		ajax: {
		delay: 200,minimumInputLength: 2,
		url: '{{ url('/user/select') }}',
			processResults: function(data) {
				var result = $.map(data.results, function (obj) {obj.text = obj.name;return obj;});
				return {results: result};
			},cache: false
		}
	});

	var category_select = $("#category").select2({
		theme: 'bootstrap',
		// tags: true,
		multiple: true,
		ajax: {
		delay: 200,minimumInputLength: 2,
		url: '{{ url('product/category/select') }}',
			processResults: function(data) {
				var result = $.map(data.results, function (obj) {obj.text = obj.text;return obj;});
				return {results: result};
			},cache: false
		}
	});


	function select2_search($el, term) {
        if(Array.isArray(term)){
            $.each(term, function(i,v){
                setTimeout(function(){
                    select2_search($el, 'id:' + v);
                }, i * 600);
            });
        }else{
            $el.select2('open');
            var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
            $search.val(term);
            $search.trigger('keyup');
            setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
        }
    }

    
    

	select2_search(user_select, 'id:{{ @$item->user_id }}');
    $("#category").val(null).change();
    @if(!empty($item->category))
	select2_search(category_select, {!! json_encode($item->category) !!});
    @endif
</script>
@endpush
