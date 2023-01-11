@extends('layouts.books')

@section('content')
<div class="page-header border-bottom">
    <div class="container">
        <div class="d-md-flex justify-content-between align-items-center py-4">
            <h1 class="page-title font-size-3 font-weight-medium m-0 text-lh-lg">Social Login</h1>
            <nav class="woocommerce-breadcrumb font-size-2">
                <a href="{{ url('/') }}" class="h-primary">Home</a>
                <span class="breadcrumb-separator mx-1"><i class="fas fa-angle-right"></i></span>
                <span>Social Login</span>
            </nav>
        </div>
    </div>
</div>
<div class="container mt-10 mb-10">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center border-0">
                <img src="{{ url('assets/images/login-success.svg') }}" alt="">
            </div>
        </div>
    </div>
</div>
@endsection