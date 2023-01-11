{{-- BTL Template - Do not delete --}}
@php
// extra_code
$parents = App\Models\ProductCategory::where('parent',0)->get();
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::select column="3" name="parent" title="Parent" :required="false" type="text" value="{{ @$item->parent }}" :options="$parents" select-title="name" select-value="id" />
    <x-form::input column="3" name="slug" title="Slug" :required="true" type="text" value="{{ @$item->slug }}" />
    <x-form::input column="3" name="name" title="English Name" :required="true" type="text" value="{!! @$item->name !!}" />
    <x-form::input column="3" name="name_bn" title="Bangla Name" :required="true" type="text" value="{!! @$item->name_bn !!}" />
    <x-form::input column="4" name="photo" title="Photo" :required="false" type="text" value="{{ @$item->photo }}" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
