{{-- BTL Template - Do not delete --}}
@php
$pg = ['bank'=>'Bank'];
@endphp

<style type="text/css">
    .h-100px{
        height: 100px;
    }
</style>
<div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
    <div class="col-md-9">
        <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
            <h1 class="display-4 font-weight-boldest mb-10">PAYOUT (AS) # {{ $item->id }}</h1>
            <div class="d-flex flex-column align-items-md-end px-0">
                <!--begin::Logo-->
                <a href="#" class="mb-5">
                    <img src="{{ asset('assets/images/payout-logo.png') }}" class="h-100px rounded" alt="">
                </a>
                <!--end::Logo-->
                <span class="d-flex flex-column align-items-md-end opacity-70">
                    <span>3rd Floor, Abedin Bhaban, Soni Akra,</span>
                    <span>Dhaka, 1236, Bangladesh</span>
                </span>
            </div>
        </div>
        <div class="border-bottom w-100"></div>
        <div class="d-flex justify-content-between pt-6">
            <div class="d-flex flex-column flex-root">
                <span class="font-weight-bolder mb-2">DATA</span>
                <span class="opacity-70">Dec 12, 2017</span>
            </div>
            <div class="d-flex flex-column flex-root">
                <span class="font-weight-bolder mb-2">INVOICE NO.</span>
                <span class="opacity-70">GS 000014</span>
            </div>
            <div class="d-flex flex-column flex-root">
                <span class="font-weight-bolder mb-2">INVOICE TO.</span>
                <span class="opacity-70">Iris Watson, P.O. Box 283 8562 Fusce RD. 
                <br>Fredrick Nebraska 20620</span>
            </div>
        </div>
    </div>
</div>

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>

    <x-form::textarea column="9" name="details" title="Payment Details" :required="true" type="text" value="{{ @$item->name }}" />
    <div class="col-md-3">
        <h3>Bank Details</h3>
        <div class="separator separator-dashed separator-border-4 mb-4"></div>
        {!! str_replace("\n", '<br>', $item->vendor->bank) !!}
    </div>
    
    <div class="col-md-12 mt-4">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
