{{-- BTL Template - Do not delete --}}
@php
$status = ['0'=>'Draft','1'=>'Publish'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::input column="4" name="name" title="Post Tile" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::input column="4" name="slug" title="Slug" :required="true" type="text" value="{{ @$item->slug }}" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <x-form::rta column="12" name="description" title="Details (EN)" :required="true" type="text" value="{{ @$item->description }}" />
    <x-form::rta column="12" name="description_bn" title="Details (BN)" :required="true" type="text" value="{{ @$item->description_bn }}" />
    @include('seo')
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
