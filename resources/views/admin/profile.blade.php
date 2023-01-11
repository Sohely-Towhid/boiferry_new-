@extends('layouts.admin')

@section('content')
@php

@endphp
<form action="" method="POST">
    @csrf
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid mt-6">
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card card-custom">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
                            </div>
                            <div class="card-toolbar">
                                <button type="submit" class="btn btn-primary font-weight-bolder mr-2">Save Changes</button>
                                <button type="reset" class="btn btn-light-primary font-weight-bolder">Cancel</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('msg')
                            <div class="alert alert-custom alert-light-danger fade show mb-10" role="alert">
                                <div class="alert-icon">
                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"></circle>
                                                <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"></rect>
                                                <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"></rect>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div class="alert-text font-weight-bold">Don’t use your name or names of family members or pets in your passwords. Don’t use numbers like your address, phone number, or birthdays, either.<br>These can be publicly available, on forms you fill out or on social media profiles, and easily accessible to hackers.</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">
                                            <i class="ki ki-close"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!--end::Alert-->
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label text-alert">Current Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input name="old_password" type="password" class="form-control form-control-lg form-control-solid" value="" required placeholder="Current password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input name="password" type="password" class="form-control form-control-lg form-control-solid" value="" required placeholder="New password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input name="password_confirmation" type="password" class="form-control form-control-lg form-control-solid" value="" required placeholder="Verify password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
