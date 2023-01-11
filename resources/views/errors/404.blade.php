@extends('layouts.books')
@section('title','404')
@section('content')
<div class="container">
    <div class="space-bottom-1 space-top-xl-2 space-bottom-xl-4">
        <div class="d-flex flex-column align-items-center pt-lg-7 pb-lg-4 pb-lg-6">
            <div class="font-weight-medium font-size-200 font-size-xs-170 text-lh-sm mt-xl-1">404</div>
            <h6 class="font-size-4 font-weight-medium mb-2">Woops, looks like this page does not exist</h6>
            <span class="font-size-2 mb-6">You could either browse our book list or go to homepage</span>
            <div class="d-flex align-items-center flex-column">
                <a href="{{ url('books') }}" class="btn btn-dark rounded-0 btn-wide height-60 width-250 font-weight-medium">All Book</a>
            </div>
        </div>
    </div>
</div>
@endsection