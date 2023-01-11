{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Subscription Setting')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Subscription Setting</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Subscription Setting</span>
                </h3>
                <div class="card-toolbar">
                    <!--<a href="" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>-->
                </div>
            </div>

            <div class="card-body pt-6">
                @include('msg')
                <form action="{{ url('setting/subscription') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    <x-form::input column="3" name="subscription_1" title="1 Month Fee" :required="true" type="number" value="{{ @$items->subscription_1->value }}" />   
                    <x-form::input column="3" name="subscription_3" title="3 Month Fee" :required="true" type="number" value="{{ @$items->subscription_3->value }}" />   
                    <x-form::input column="3" name="subscription_6" title="6 Month Fee" :required="true" type="number" value="{{ @$items->subscription_6->value }}" />   
                    <x-form::input column="3" name="subscription_12" title="12 Month Fee" :required="true" type="number" value="{{ @$items->subscription_12->value }}" />   
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush