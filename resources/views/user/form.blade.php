{{-- BTL Template - Do not delete --}}
@php
// extra_code
$status = ['Pending','Active','Banned'];
$role = [
    'admin'=>'Admin', 
    'accounts'=>'Accounts', 
    'product-manager' => "Product Manager",
    'key-account-manager' => 'Key Account Manager',
    'marketing' => 'Marketing',
    'crm' => 'CRM',
    'logistics' => 'Logistics',
    'vendor' => 'Seller (Vendor)',
    'customer' => 'Customer',
];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::input column="4" name="name" title="Full Name" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::input column="4" name="mobile" title="Mobile" :required="true" type="text" value="{{ @$item->mobile }}" />
    <x-form::input column="4" name="email" title="Email" :required="true" type="email" value="{{ @$item->email }}" />
    <x-form::input column="3" name="balance" title="Balance" :required="true" type="number" value="{{ @$item->balance }}" />
    <x-form::input column="3" name="password" title="Password" :required="false" type="text" value="" />
    <x-form::select column="3" name="role" title="Role" :required="true" value="{{ @$item->role }}" :options="$role" />
    <x-form::select column="3" name="status" title="Status" :required="true" value="{{ @$item->status }}" :options="$status" />
    <x-form::input type="text" column="3" name="dob" title="Date of Birth" :required="false" value="{{ @$item->dob }}"/>
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>
</div>
<!-- form end -->

@push('scripts')
@endpush
