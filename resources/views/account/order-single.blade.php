@extends('layouts.books')
@section('title','Order#'.$item->id)
@section('content')
@php 
$user = Auth::user();
$status = ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$status_color = ['-','dark','info','info', 'success', 'danger','warning','info'];
$total = $item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount;
@endphp
<style>
    .bkash-button{
        background: #d3417f;
        border-color: #d3417f;
    }
    .bkash-button:hover{
        background: #e11e70;
        border-color: #e11e70;
    }

.pg_button {
  padding: 0;
  background: #009578;
  border: none;
  outline: none;
  border-radius: 5px;
  overflow: hidden;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  float: left;
}
.pg_container{
     height: 60px;
   display: flex; 
}
.pg_button:hover {
  background: #008168;
}

.pg_button:active {
  background: #006e58;
}

.pg_button__text,
.pg_button__icon {
  display: inline-flex;
  align-items: center;
  padding: 0 24px;
  color: #fff;
  height: 100%;
}
.pg_button__text{
    padding: 0 20px;
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    min-width: 130px;
}
.pg_button__text .line1{
    font-size: 12px;
}

.pg_button__icon {
  font-size: 1.5em;
  background: rgba(0, 0, 0, 0.08);
}

.bg-bkash{
    background: #d3417f;
    border-color: #d3417f;
}
.bg-bkash:hover{
    background: #e11e70;
    border-color: #e11e70;
}

.bg-nagad{
    background: #ef9014;
    border-color: #ef9014;
}
.bg-nagad:hover{
    background: #e77622;
    border-color: #e77622;
}

.bg-ssl{
    background: #2b6cd3;
    border-color: #2b6cd3;
}
.bg-ssl:hover{
    background: #2859a6;
    border-color: #2859a6;
}
.fa-bkash{
    background: url({{ asset('assets/images/bkash_w.svg') }});
    width: 30px;
    height: 30px;
    background-position: center center;
    background-repeat: no-repeat;
}
.fa-nagad{
    background: url({{ asset('assets/images/nagad_w.svg') }});
    width: 30px;
    height: 30px;
    background-position: center center;
    background-repeat: no-repeat;
}
.h60{height: 60px;display: flex;
    justify-content: center;}

</style>
<main id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3 border-right">
                <h6 class="font-weight-medium font-size-7 pt-5 pt-lg-8  mb-5 mb-lg-7">My account</h6>
                <div class="tab-wrapper">
                    @include('account.menu')
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
                        <div class="pt-5 pt-lg-8 pl-md-5 pl-lg-9 space-bottom-2 space-bottom-lg-3 mb-xl-1">
                            <h6 class="font-weight-medium font-size-7 ml-lg-6 pb-xl-1">Order#{{ $item->id }}</h6>
                            @include('msg')
                            @if(@$item->pre_order)
                            <div class="alert alert-info">
                                This order has pre-order books, we will ship it as soon as the book published.<br>এই অর্ডারে প্রি-অর্ডার বই রয়েছে, বই প্রকাশিত হওয়ার সাথে সাথে আমরা আপনাকে পাঠাবো।
                            </div>
                            @endif

                            @if(request()->payment=='success')
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Thank you!</strong> {{ request()->get('msg', 'your payment was successfull.') }}
                            </div>
                            @endif
                            @if(request()->payment=='failed')
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Sorry!</strong> {{ request()->get('msg', 'your payment didn\'t go through.') }}
                            </div>
                            @endif

                            <div class="max-width-890 mx-auto">
                                <div class="text-center mb-3 h60">
                                    @if($item->payment=='sslcommerz' && $item->status == 1)
                                    <button type="button" class="pg_button bg-ssl" id="sslczPayBtn" token="" postdata="payment=yes" order="{{ $item->id }}" endpoint="?payment=yes"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay with</span><span class="line2">SSLCommerz</span></span><span class="pg_button__icon"><i class="fab fa-cc-visa"></i></span></div></button>
                                    {{-- <button class="your-button-class btn btn-info text-white mt-5" id="sslczPayBtn" token="" postdata="payment=yes" order="{{ $item->id }}" endpoint="?payment=yes">Pay With SSLCommerz</button> --}}
                                    @endif
                                    @if($item->payment=='cod' && $item->status == 1)
                                    <button type="button" class="pg_button bg-ssl" id="sslczPayBtn" token="" postdata="payment=yes" order="{{ $item->id }}" endpoint="?payment=yes"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay 100 TK with</span><span class="line2">SSLCommerz</span></span><span class="pg_button__icon"><i class="fab fa-cc-visa"></i></span></div></button>
                                    {{-- <button onclick="makeBkash();" type="button" class="pg_button bg-bkash ml-3"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay 100 TK with</span><span class="line2">bKash</span></span><span class="pg_button__icon"><i class="fab fa-bkash"></i></span></div></button> --}}
                                    <button onclick="makeNagad();" type="button" class="pg_button bg-nagad ml-3"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay 100 TK with</span><span class="line2">Nagad</span></span><span class="pg_button__icon"><i class="fab fa-nagad"></i></span></div></button>
                                    <button onclick="makeBkash();" type="button" class="pg_button bg-bkash ml-3"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay with</span><span class="line2">bKash</span></span><span class="pg_button__icon"><i class="fab fa-bkash"></i></span></div></button>
                                    {{-- <button class="your-button-class btn btn-info text-white mt-5 mr-3" id="sslczPayBtn" token="" postdata="payment=yes" order="{{ $item->id }}" endpoint="?payment=yes">Pay With SSLCommerz</button> --}}
                                    {{-- <button type="button" onclick="makeBkash();" class="bkash-button btn btn-info text-white mt-5 mr-3">Pay With bKash</button> --}}
                                    {{-- <button type="button" onclick="makeNagad();" class="nagad-button btn btn-info text-white mt-5">Pay With Nagad</button> --}}
                                    @endif
                                    @if($item->payment=='bkash' && $item->status == 1)
                                    <button onclick="makeBkash();" type="button" class="pg_button bg-bkash ml-3"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay with</span><span class="line2">bKash</span></span><span class="pg_button__icon"><i class="fab fa-bkash"></i></span></div></button>
                                    {{-- <button type="button" onclick="makeBkash();" class="bkash-button btn btn-info text-white mt-5">Pay With bKash</button> --}}
                                    @endif
                                    @if($item->payment=='nagad' && $item->status == 1)
                                    <button onclick="makeNagad();" type="button" class="pg_button bg-nagad ml-3"><div class="pg_container"><span class="pg_button__text"><span class="line1">Pay with</span><span class="line2">Nagad</span></span><span class="pg_button__icon"><i class="fab fa-nagad"></i></span></div></button>
                                    {{-- <button type="button" onclick="makeNagad();" class="nagad-button btn btn-info text-white mt-5">Pay With Nagad</button> --}}
                                    @endif
                                </div>
                                <div class="border overflow-auto mb-5 overflow-md-visible">
                                    <ul class="progressbar border-bottom pt-6 pb-5">
                                        <li class="{{ ($item->status >= 2) ? 'active' : '' }}">Order<br>Processed</li>
                                        <li class="{{ ($item->status >= 2) ? 'active' : '' }}">Payment<br>
                                            @if(in_array($item->payment,['sslcommerz','bkash','nagad']) && $item->status>=2)
                                            PAID
                                            @elseif($item->partial_payment>0)
                                            COD (Delivery Paid)
                                            @else
                                            COD
                                            @endif
                                        </li>
                                        <li class="{{ ($item->packed >= 100) ? 'active' : '' }}">Order<br>Packed</li>
                                        <li class="{{ ($item->status >= 3 && $item->status<7) ? 'active' : '' }}">Order<br>Shipped</li>
                                        <li class="{{ ($item->status >=4 && $item->status<7) ? 'active' : '' }}">Order<br>Delivered</li>
                                    </ul>
                                    @if($item->tracking)
                                    <div class="border-bottom p-5" id="winx-tracking">{{ str_replace(['WINX:'],'',$item->tracking) }}</div>
                                    @endif
                                    <div class="border-bottom mb-5 pb-5 pt-5 overflow-auto overflow-md-visible">
                                        <div class="pl-3">
                                            <table class="table table-borderless mb-0 ml-1">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="font-size-2 font-weight-normal py-0">Order number:</th>
                                                    <th scope="col" class="font-size-2 font-weight-normal py-0">Date:</th>
                                                    <th scope="col" class="font-size-2 font-weight-normal py-0 text-md-center">Total: </th>
                                                    <th scope="col" class="font-size-2 font-weight-normal py-0 text-md-right pr-md-5">Order Status:</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row" class="pr-0 py-0 font-weight-medium">{{ $item->id }}</th>
                                                    <td class="pr-0 py-0 font-weight-medium">{{ $item->created_at->format('F d, Y') }}</td>
                                                    <td class="pr-0 py-0 font-weight-medium text-md-center">৳ @money($total)</td>
                                                    <td class="pr-md-4 py-0 font-weight-medium text-md-right  pr-md-5">{{ $status[$item->status] }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-5 pb-6 px-3 px-md-4">
                                        <h6 class="font-size-3 on-weight-medium mb-4 pb-1 ml-1">Order Details</h6>
                                        <table class="table table-borderless mb-0 ml-1 ">
                                            <tbody>
                                                @foreach($item->metas as $meta)
                                                @if($meta->book_id)
                                                <tr>
                                                    <td>
                                                        <h6 class="font-size-2 font-weight-normal mb-1 pl-0">
                                                            {{ $meta->product->title_bn }} <br> {{ $meta->product->author_bn }}
                                                            @if(@$meta->product->pre_order)
                                                            <span class="badge badge-info">Pre-Order</span>
                                                            @endif
                                                        </h6>
                                                        <span class="font-size-2 text-gray-600">
                                                            @php $pro_de = @implode(', ', array_filter([@$meta->product->type,@$meta->product->language])); @endphp
                                                            {!! ($pro_de) ? $pro_de.'<br>' : '' !!}
                                                        </span>
                                                    </td>
                                                    <td><span class="font-size-2 ml-4 ml-md-8">৳ @if($meta->product->rate>$meta->rate)<s>{{ $meta->product->rate }}</s>@endif {{ $meta->rate }}</span> x {{ $meta->quantity }}</span></td>
                                                    <td class="text-right"><span class="font-weight-medium font-size-2">৳ @money($meta->rate * $meta->quantity)</span></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="border-bottom mb-5 pb-5">
                                        <ul class="list-unstyled px-3 pl-md-5 pr-md-4 mb-0">
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium font-size-2">Subtotal:</span>
                                                <span class="font-weight-medium font-size-2">৳ @money($item->total)</span>
                                            </li>
                                            @if($item->gift_wrap>0)
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium font-size-2">Gift Wrap:</span>
                                                <span class="font-weight-medium font-size-2">৳ @money($item->gift_wrap)</span>
                                            </li>
                                            @endif
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium font-size-2">Shipping:</span>
                                                <span class="font-weight-medium font-size-2">@money($item->shipping)</span>
                                            </li>
                                            @if($item->coupon_discount>0)
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium font-size-2">Coupon Discount:</span>
                                                <span class="font-weight-medium font-size-2">৳ @money($item->coupon_discount)</span>
                                            </li>
                                            @endif
                                            <li class="d-flex justify-content-between pt-2">
                                                <span class="font-weight-medium font-size-2">Payment Method:</span>
                                                <span class="font-weight-medium font-size-2">{{ strtoupper($item->payment) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="border-bottom mb-5 pb-4">
                                        <div class="px-3 pl-md-5 pr-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span class="font-size-2 font-weight-medium">Total</span>
                                                <span class="font-weight-medium fon-size-2">৳ @money($total)</span>
                                            </div>
                                        </div>
                                        @if($item->partial_payment>0)
                                        <div class="px-3 pl-md-5 pr-md-4 pt-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="font-size-2 font-weight-medium text-danger">Due (Paid @money($item->partial_payment))</span>
                                                <span class="font-weight-medium fon-size-2 text-danger">৳ @money($total - $item->partial_payment)</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="px-3 pl-md-5 pr-md-4 mb-6 pb-xl-1">
                                        <div class="row row-cols-1 row-cols-md-2">
                                            <div class="col">
                                                <div class="mb-6 mb-md-0">
                                                    <h6 class="font-weight-medium font-size-22 mb-3">Billing Address
                                                    </h6>
                                                    <address class="d-flex flex-column mb-0">
                                                        <span class="text-gray-600 font-size-2">{{ @$item->billing_address->name }}</span>
                                                        <span class="text-gray-600 font-size-2">{{ @$item->billing_address->mobile }}</span>
                                                        <span class="text-gray-600 font-size-2">{{ @$item->billing_address->street }}</span>
                                                        <span class="text-gray-600 font-size-2">{{ @$item->billing_address->district }}</span>
                                                        <span class="text-gray-600 font-size-2">{{ @implode(', ',array_filter([@$item->billing_address->city, @$item->billing_address->postcode])) }}</span>
                                                        <span class="text-gray-600 font-size-2">{{ @$item->billing_address->country }}</span>
                                                    </address>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h6 class="font-weight-medium font-size-22 mb-3">Shipping Address
                                                </h6>
                                                <address class="d-flex flex-column mb-0">
                                                    <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->name }}</span>
                                                    <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->mobile }}</span>
                                                    <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->street }}</span>
                                                    <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->district }}</span>
                                                    <span class="text-gray-600 font-size-2">{{ @implode(', ',array_filter([@$item->shipping_address->city, @$item->shipping_address->postcode])) }}</span>
                                                    <span class="text-gray-600 font-size-2">{{ @$item->shipping_address->country }}</span>
                                                </address>
                                            </div>
                                        </div>
                                        @if($item->note)
                                        <div class="row py-3">
                                            <div class="col-md-12">
                                                <strong>Note: </strong> {{ $item->note }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
@if($item->tracking)
<script type="text/javascript" src="//winx.com.bd/assets/js/embeded.js?time={{ time() }}"></script>
@endif
<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "{{ config('services.ssl.js') }}?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };
        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);

    @if(in_array($item->payment,['cod','bkash']) && $item->status == 1)
    function makeBkash(){
        $.ajax({
            method: "POST",
            url: "{{ url('/my-account/order/'.$item->id) }}",
            data: {'bkash': true},
            beforeSend: function(){
                $(".ajax-loader").show();
            },
            success: function(data){
                $(".ajax-loader").hide();
                if(data.url){
                    window.location = data.url;
                }else{
                    Swal.fire('Ops!','Something Went Wrong!','error');
                }
            },error: function(data){
                $(".ajax-loader").hide();
                Swal.fire('Ops!','Something Went Wrong!','error');
            }
        });
    }
    @endif

    @if(in_array($item->payment,['cod','nagad']) && $item->status == 1)
    function makeNagad(){
        $.ajax({
            method: "POST",
            url: "{{ url('/my-account/order/'.$item->id) }}",
            data: {'nagad': true},
            beforeSend: function(){
                $(".ajax-loader").show();
            },
            success: function(data){
                $(".ajax-loader").hide();
                if(data.url){
                    window.location = data.url;
                }else{
                    Swal.fire('Ops!','Something Went Wrong!','error');
                }
            },error: function(data){
                $(".ajax-loader").hide();
                Swal.fire('Ops!','Something Went Wrong!','error');
            }
        });
    }
    @endif

    $(document).ready(function() {
        @if(in_array($item->payment, ['sslcommerz']) && request()->payment=='yes')
        setTimeout(function(){
            $("#sslczPayBtn").click();
        },1500);
        @endif 
        @if(in_array($item->payment, ['bkash']) && request()->payment=='yes')
        setTimeout(function(){
            makeBkash();
        },1500);
        @endif
        @if(in_array($item->payment, ['nagad']) && request()->payment=='yes')
        setTimeout(function(){
            makeNagad();
        },1500);
        @endif  
        @if(@$fb || Session::get('fb'))
        fbq('track', 'Purchase', {value: {{ $total }}, currency: 'BDT'});
        @endif
    });
</script>
@endpush