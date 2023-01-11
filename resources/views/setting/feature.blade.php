{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Books Setting')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Books Setting (feature)</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Home Page Setting for Books Site (feature)</span>
                </h3>
                <div class="card-toolbar">
                </div>
            </div>

            <div class="card-body pt-6">
                <form action="{{ url('setting/feature') }}" method="POST" enctype="multipart/form-data">
                    <!-- form start -->
                    <div class="row">
                        @csrf
                        <div class="col-md-12">@include('msg')</div>
                        @for($i=1; $i<=3; $i++)
                        @php
                        $dy_ver = 'feature_'.$i;
                        $se = $items->$dy_ver->value;
                        @endphp
                        <div class="col">
                            <label>Status</label>
                            <span class="switch">
                                <label>
                                    <input name="status[{{ $i }}]" type="checkbox" {{ ($items->$dy_ver->status) ? 'checked' : '' }} name="select"/>
                                    <span></span>
                                </label>
                            </span>
                        </div>
                        <x-form::input column="3" name="bg[{{ $i }}]" title="Background Color (Hex)" :required="false" type="color" value="{{ $se->bg }}" />
                        <x-form::image column="3" name="image[{{ $i }}]" title="Feature Image" :required="false" value="{{ $se->image }}" size="431*192px" />
                        <x-form::input column="5" name="link[{{ $i }}]" title="Action Link" :required="false" value="{{ $se->link }}" />
                        @endfor
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Save Setting</button>
                        </div>
                    </div>
                    <!-- form end -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush