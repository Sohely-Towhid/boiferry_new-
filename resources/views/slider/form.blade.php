{{-- BTL Template - Do not delete --}}
@php
// extra_code
$type = ['Book'=>'Book','Main'=>'Main'];
$status = ['0'=>'Draft','1'=>'Active'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::select column="4" name="type" title="Slider Type" :required="true" type="text" value="{{ @$item->type }}" :options="$type" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <x-form::input column="4" name="text[line_1]" title="Line 1" :required="false" type="text" value="{{ @$item->text->line_1 }}" />
    <x-form::input column="4" name="text[line_2]" title="Line 2" :required="false" type="text" value="{{ @$item->text->line_2 }}" />
    <x-form::input column="4" name="text[line_3]" title="Line 3" :required="false" type="text" value="{{ @$item->text->line_3 }}" />
    <x-form::input column="4" name="text[link]" title="Link" :required="false" type="text" value="{{ @$item->text->link }}" />
    <x-form::input column="4" name="text[link_text]" title="Link Text" :required="false" type="text" value="{{ @$item->text->link_text }}" />
    
    <x-form::image column="4" name="image" size="1200 x 300 px" title="Slider Image" :required="true" value="{{ @$item->image }}" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
