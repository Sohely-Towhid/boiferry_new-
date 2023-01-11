{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','New Product Category')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">New Product Category</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Create Product Category</span>
                </h3>
                <div class="card-toolbar">
                    <!--<a href="" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1"><i class="fas fa-plus"></i> Add New</a>-->
                </div>
            </div>

            <div class="card-body pt-6">
                <form action="{{ url('product/category') }}" method="POST" enctype="multipart/form-data">
                    @include('productcategory.form',['button'=>'Save Product Category'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush