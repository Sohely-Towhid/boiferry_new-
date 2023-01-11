{{-- BTL Template - Do not delete --}}
@extends('layouts.seller')
@section('title','Search')
@section('content')
@php
$user = Auth::user();
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
@endpush