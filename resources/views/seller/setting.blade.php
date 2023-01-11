{{-- BTL Template - Do not delete --}}
@extends('layouts.seller')
@section('title','Setting')
@section('content')
@php
$user = Auth::user();
$banks = ['Sonali Bank Limited' => 'Sonali Bank Limited','Janata Bank Limited' => 'Janata Bank Limited','Agrani Bank Limited' => 'Agrani Bank Limited','Rupali Bank Limited' => 'Rupali Bank Limited','BASIC Bank Limited' => 'BASIC Bank Limited','Bangladesh Development Bank' => 'Bangladesh Development Bank','AB Bank Limited' => 'AB Bank Limited','Bangladesh Commerce Bank Limited' => 'Bangladesh Commerce Bank Limited','Bank Asia Limited' => 'Bank Asia Limited','BRAC Bank Limited' => 'BRAC Bank Limited','Citizens Bank PLC' => 'Citizens Bank PLC','City Bank Limited' => 'City Bank Limited','Community Bank Bangladesh Limited' => 'Community Bank Bangladesh Limited','Dhaka Bank Limited' => 'Dhaka Bank Limited','Dutch-Bangla Bank Limited' => 'Dutch-Bangla Bank Limited','Eastern Bank Limited' => 'Eastern Bank Limited','IFIC Bank Limited' => 'IFIC Bank Limited','Jamuna Bank Limited' => 'Jamuna Bank Limited','Meghna Bank Limited' => 'Meghna Bank Limited','Mercantile Bank Limited' => 'Mercantile Bank Limited','Midland Bank Limited' => 'Midland Bank Limited','Modhumoti Bank Limited' => 'Modhumoti Bank Limited','Mutual Trust Bank Limited' => 'Mutual Trust Bank Limited','National Bank Limited' => 'National Bank Limited','National Credit & Commerce Bank Limited' => 'National Credit & Commerce Bank Limited','NRB Bank Limited' => 'NRB Bank Limited','NRB Commercial Bank Ltd' => 'NRB Commercial Bank Ltd','One Bank Limited' => 'One Bank Limited','Padma Bank Limited' => 'Padma Bank Limited','Premier Bank Limited' => 'Premier Bank Limited','Prime Bank Limited' => 'Prime Bank Limited','Pubali Bank Limited' => 'Pubali Bank Limited','Shimanto Bank Ltd' => 'Shimanto Bank Ltd','Southeast Bank Limited' => 'Southeast Bank Limited','South Bangla Agriculture and Commerce Bank Limited' => 'South Bangla Agriculture and Commerce Bank Limited','Trust Bank Limited' => 'Trust Bank Limited','United Commercial Bank Ltd' => 'United Commercial Bank Ltd','Uttara Bank Limited' => 'Uttara Bank Limited','Bengal Commercial Bank Ltd' => 'Bengal Commercial Bank Ltd'];
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom">
            <!--begin::Card header-->
            <div class="card-header card-header-tabs-line nav-tabs-line-3x">
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                        <li class="nav-item mr-3">
                            <a class="nav-link active" data-toggle="tab" href="#kt_user_edit_tab_1">
                                <span class="nav-icon">
                                    <span class="svg-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="nav-text font-size-lg">Store Details</span>
                            </a>
                        </li>
                        <li class="nav-item mr-3">
                            <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_2">
                                <span class="nav-icon">
                                    <span class="svg-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M22,15 L22,19 C22,20.1045695 21.1045695,21 20,21 L8,21 C5.790861,21 4,19.209139 4,17 C4,14.790861 5.790861,13 8,13 L20,13 C21.1045695,13 22,13.8954305 22,15 Z M7,19 C8.1045695,19 9,18.1045695 9,17 C9,15.8954305 8.1045695,15 7,15 C5.8954305,15 5,15.8954305 5,17 C5,18.1045695 5.8954305,19 7,19 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M15.5421357,5.69999981 L18.3705628,8.52842693 C19.1516114,9.30947552 19.1516114,10.5758055 18.3705628,11.3568541 L9.88528147,19.8421354 C8.3231843,21.4042326 5.79052439,21.4042326 4.22842722,19.8421354 C2.66633005,18.2800383 2.66633005,15.7473784 4.22842722,14.1852812 L12.7137086,5.69999981 C13.4947572,4.91895123 14.7610871,4.91895123 15.5421357,5.69999981 Z M7,19 C8.1045695,19 9,18.1045695 9,17 C9,15.8954305 8.1045695,15 7,15 C5.8954305,15 5,15.8954305 5,17 C5,18.1045695 5.8954305,19 7,19 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M5,3 L9,3 C10.1045695,3 11,3.8954305 11,5 L11,17 C11,19.209139 9.209139,21 7,21 C4.790861,21 3,19.209139 3,17 L3,5 C3,3.8954305 3.8954305,3 5,3 Z M7,19 C8.1045695,19 9,18.1045695 9,17 C9,15.8954305 8.1045695,15 7,15 C5.8954305,15 5,15.8954305 5,17 C5,18.1045695 5.8954305,19 7,19 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="nav-text font-size-lg">Logo & Banner</span>
                            </a>
                        </li>
                        <li class="nav-item mr-3">
                            <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_3">
                                <span class="nav-icon">
                                    <span class="svg-icon">
                                       <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M14.5,11 C15.0522847,11 15.5,11.4477153 15.5,12 L15.5,15 C15.5,15.5522847 15.0522847,16 14.5,16 L9.5,16 C8.94771525,16 8.5,15.5522847 8.5,15 L8.5,12 C8.5,11.4477153 8.94771525,11 9.5,11 L9.5,10.5 C9.5,9.11928813 10.6192881,8 12,8 C13.3807119,8 14.5,9.11928813 14.5,10.5 L14.5,11 Z M12,9 C11.1715729,9 10.5,9.67157288 10.5,10.5 L10.5,11 L13.5,11 L13.5,10.5 C13.5,9.67157288 12.8284271,9 12,9 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="nav-text font-size-lg">Change Password</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_4">
                                <span class="nav-icon">
                                    <span class="svg-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M6.5,16 L7.5,16 C8.32842712,16 9,16.6715729 9,17.5 L9,19.5 C9,20.3284271 8.32842712,21 7.5,21 L6.5,21 C5.67157288,21 5,20.3284271 5,19.5 L5,17.5 C5,16.6715729 5.67157288,16 6.5,16 Z M16.5,16 L17.5,16 C18.3284271,16 19,16.6715729 19,17.5 L19,19.5 C19,20.3284271 18.3284271,21 17.5,21 L16.5,21 C15.6715729,21 15,20.3284271 15,19.5 L15,17.5 C15,16.6715729 15.6715729,16 16.5,16 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6 C3,4.8954305 3.8954305,4 5,4 Z M15.5,15 C17.4329966,15 19,13.4329966 19,11.5 C19,9.56700338 17.4329966,8 15.5,8 C13.5670034,8 12,9.56700338 12,11.5 C12,13.4329966 13.5670034,15 15.5,15 Z M15.5,13 C16.3284271,13 17,12.3284271 17,11.5 C17,10.6715729 16.3284271,10 15.5,10 C14.6715729,10 14,10.6715729 14,11.5 C14,12.3284271 14.6715729,13 15.5,13 Z M7,8 L7,8 C7.55228475,8 8,8.44771525 8,9 L8,11 C8,11.5522847 7.55228475,12 7,12 L7,12 C6.44771525,12 6,11.5522847 6,11 L6,9 C6,8.44771525 6.44771525,8 7,8 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="nav-text font-size-lg">Payout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <div class="tab-content">
                    <!--begin::Tab-->
                    <div class="tab-pane show px-7 active" id="kt_user_edit_tab_1" role="tabpanel">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    @include('msg')
                                </div>
                                <div class="col-xl-2"></div>
                                <div class="col-xl-7 my-2">
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left" for="name">Shop Name <sup style="color:red;">*</sup></label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" name="name" id="name" type="text" readonly required value="{{ $user->vendor->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left" for="slug">Shop Slug / URL <sup style="color:red;">*</sup></label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" name="slug" id="slug" type="text" readonly required value="{{ $user->vendor->slug }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left" for="mobile">Shop Mobile <sup style="color:red;">*</sup></label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" name="mobile" id="mobile" type="tel" required value="{{ $user->vendor->mobile }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left" for="email">Shop Email <sup style="color:red;">*</sup></label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" name="email" id="email" type="email" readonly required value="{{ $user->vendor->email }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left" for="address">Shop Address <sup style="color:red;">*</sup></label>
                                        <div class="col-9">
                                            <textarea class="form-control form-control-lg form-control-solid" name="address" id="address" required>{{ $user->vendor->address }}</textarea> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col-9"><button type="submit" class="btn btn-primary">Update Details</button></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Tab-->
                    <!--begin::Tab-->
                    <div class="tab-pane px-7" id="kt_user_edit_tab_2" role="tabpanel">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="images" value="yes">
                            <div class="row">
                                <div class="col-12">
                                    @include('msg')
                                </div>

                                <div class="col-md-2"></div>
                                <x-form::image column="4" name="logo" title="Store Logo" size="500 x 500 px" :required="true" value="{{ showImage(@$user->vendor->logos[0], 'lg') }}" />
                                <div class="col-md-6"></div>

                                <div class="col-md-2"></div>
                                <x-form::image column="2" name="banner_1" title="Banner 1" size="800 x 420 px" :required="false" value="{{ showImage(@$user->vendor->banners[0]) }}" />
                                <div class="col-md-1"></div>
                                <x-form::image column="2" name="banner_2" title="Banner 2" size="800 x 420 px" :required="false" value="{{ showImage(@$user->vendor->banners[1]) }}" />
                                <div class="col-md-1"></div>
                                <x-form::image column="2" name="banner_3" title="Banner 3" size="800 x 420 px" :required="false" value="{{ showImage(@$user->vendor->banners[2]) }}" />

                                
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-9"><button type="submit" class="btn btn-primary">Update Details</button></div>
                            </div>
                        </form>
                    </div>
                    <!--end::Tab-->
                    <!--begin::Tab-->
                    <div class="tab-pane px-7" id="kt_user_edit_tab_3" role="tabpanel">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="password_change" value="yes">
                            <div class="row">
                                <div class="col-12">
                                    @include('msg')
                                </div>

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xl-2"></div>
                                        
                                        <div class="col-xl-7">
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left" for="old_password">Current Password</label>
                                                <div class="col-9">
                                                    <input class="form-control form-control-lg form-control-solid mb-1" type="text" name="old_password" id="old_password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left" for="password">New Password</label>
                                                <div class="col-9">
                                                    <input class="form-control form-control-lg form-control-solid" type="text" name="password" id="password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left" for="password_confirmation">Verify Password</label>
                                                <div class="col-9">
                                                    <input class="form-control form-control-lg form-control-solid" type="text" name="password_confirmation" id="password_confirmation" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3"></div>
                                                <div class="col-9"><button type="submit" class="btn btn-primary">Update Password</button></div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                
                            </div>
                        </form>
                    </div>
                    <!--end::Tab-->
                    <!--begin::Tab-->
                    <div class="tab-pane px-7" id="kt_user_edit_tab_4" role="tabpanel">
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" name="banks" value="yes">
                            <div class="row">
                                <x-form::select column="4" name="bank[bank]" title="Bank Name" :required="true" type="text" value="{{ @$user->vendor->details['bank']['bank'] }}" :options="$banks" />
                                <x-form::input column="4" name="bank[name]" title="Account Name" :required="true" type="text" value="{{ @$user->vendor->details['bank']['name'] }}" />
                                <x-form::input column="4" name="bank[number]" title="Account Number" :required="true" type="text" value="{{ @$user->vendor->details['bank']['number'] }}" />
                                <x-form::input column="4" name="bank[branch]" title="Branch Name" :required="true" type="text" value="{{ @$user->vendor->details['bank']['branch'] }}" />
                                <x-form::input column="4" name="bank[routing]" title="Routing Number" :required="false" type="text" value="{{ @$user->vendor->details['bank']['routing'] }}" />
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update Bank Details</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Tab-->
                </div>
            </div>
            <!--begin::Card body-->
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush