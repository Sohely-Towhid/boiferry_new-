{{-- BTL Template - Do not delete --}}
@php
$status = ['0'=>'Draft', '1'=>'Active'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::input column="4" name="page" title="Page URL" :required="false" type="text" value="{{ @$item->page }}" />
    <x-form::input column="4" name="link" title="Click Link" :required="false" type="text" value="{{ @$item->link }}" />
    <x-form::input column="4" name="max_show" title="Max Show" :required="false" type="number" value="{{ @$item->max_show }}" />
    <x-form::image column="4" name="image" title="Image" size="600x600px" :required="true" value="{{ @$item->image }}" />
    <x-form::input column="4" class="date" name="expire" title="Expire" :required="false" type="text" value="{{ @$item->expire }}" />
    <x-form::input column="2" name="delay" title="Delay (in Second)" type="number" :required="true" value="{{ @$item->delay }}" />
    <x-form::select column="2" name="status" title="Status" :required="true" value="{{ @$item->status }}" :options='$status' />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
<script>
    $('#expire').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('.date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('.date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>
@endpush
