{{-- BTL Template - Do not delete --}}
@php
$status = ['','Pending', 'Active','Expired'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::select column="4" name="user_id" title="User" :required="true" type="text" value="{{ @$item->user_id }}" :options="[]"/>
    <x-form::input column="4" name="amount" title="Amount" :required="true" type="text" value="{{ @$item->amount }}" />
    <x-form::input column="4" name="validity_days" title="Validity Days" :required="true" type="text" value="{{ @$item->validity_days }}" />
    <x-form::input column="4" name="payment" title="Payment Details" :required="true" type="text" value="{!! @$item->payment !!}" />
    <x-form::input column="4" name="expire" title="Expire" :required="true" type="text" value="{{ @$item->expire }}" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
<script>
    var user_select = $("#user_id").select2({
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

    function select2_search($el, term) {
        $el.select2('open');
        var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
        $search.val(term);
        $search.trigger('keyup');
    }

    @if(@$item->user_id)select2_search(user_select, 'id:{{ @$item->user_id }}');@endif

</script>
@endpush
