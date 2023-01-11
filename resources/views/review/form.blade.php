{{-- BTL Template - Do not delete --}}
@php
$status = ['Draft','Pending','Published','Rejected'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <div class="col-md-12">
        <h3 class="mb-3">
        @if($item->book_id>0)
        {{ $item->book->title }} / {{ $item->book->title_bn }}
        @endif
        @if($item->product_id>0)
        {{ $item->product->name }} / {{ $item->product->name_bn }}
        @endif
        </h3>
    </div>
    <x-form::input column="4" name="name" title="Full Name" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::input column="4" name="star" title="Star" :required="true" type="text" value="{{ @$item->star }}" />
    <x-form::select column="4" name="status" title="Status" :required="true" type="text" value="{{ @$item->status }}" :options="$status" />
    <x-form::textarea column="12" name="message" title="Message" :required="true" type="text" value="{{ @$item->message }}" />
    <x-form::textarea column="12" name="comment" title="Admin Replay" :required="false" type="text" value="{{ @$item->comment }}" />
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
