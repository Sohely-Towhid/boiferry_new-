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
    <x-form::input column="2" name="category" title="Category" :required="true" type="text" value="{{ @$item->category }}" />
    <x-form::select column="2" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <x-form::rta column="9" name="description" title="Category" :required="true" type="text" value="{{ @$item->description }}" />
    <x-form::image column="3" name="image" title="Featured Image" size="1400 x 650px" :required="false" value="{{ @$item->image }}" />
    @include('seo')
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
