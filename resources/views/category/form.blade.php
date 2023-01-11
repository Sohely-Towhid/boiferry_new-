{{-- BTL Template - Do not delete --}}
@php
// extra_code
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::input column="4" name="slug" title="SLUG" :required="true" type="text" value="{{ @$item->slug }}" />
    <x-form::input column="4" name="name" title="Name" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::input column="4" name="name_bn" title="Name in Bangla" :required="true" type="text" value="{{ @$item->name_bn }}" />
    <x-form::image column="4" name="photo" title="Photo" size="1200x1200px" :required="false" type="text" value="{{ @$item->photo }}" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
