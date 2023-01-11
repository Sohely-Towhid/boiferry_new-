@extends('layouts.books')
@section('title','500')
@section('content')
<div class="container">
    <div class="space-bottom-1 space-top-xl-2 space-bottom-xl-4">
        <div class="d-flex flex-column align-items-center pt-lg-7 pb-lg-4 pb-lg-6">
            <div class="font-weight-medium font-size-200 font-size-xs-170 text-lh-sm mt-xl-1">500</div>
            <h6 class="font-size-4 font-weight-medium mb-2">Sorry, unexpected error</h6>
            <span class="font-size-2 mb-6">We are working on fixing the problem. Be back soon.</span>
            <div class="d-flex align-items-center flex-column">
                <a href="{{ url('books') }}" class="btn btn-dark rounded-0 btn-wide height-60 width-250 font-weight-medium">All Book</a>
            </div>
        </div>
    </div>
</div>
@endsection