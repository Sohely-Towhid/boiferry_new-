@extends('layouts.books')
@section('title',__('web.Orders'))
@section('content')
@php 
$user = Auth::user();
if(request()->type=='shipping'){
    $items = App\Models\Invoice::where('status', 3)->where('user_id',$user->id)->orderBy('id','desc')->paginate(10);
}else{
    $items = App\Models\Invoice::where('status','!=', 0)->where('user_id',$user->id)->orderBy('id','desc')->paginate(10);
}
$status = ['-','Pending','Processing','Shipped', 'Completed', 'Cancelled','Refunded','Packed'];
$status_color = ['-','dark','info','info', 'success', 'danger','warning','info'];
@endphp
<main id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3 border-right">
                <h6 class="font-weight-medium font-size-7 pt-5 pt-lg-8  mb-5 mb-lg-7">{{ __('web.My Account') }}</h6>
                <div class="tab-wrapper">
                    @include('account.menu')
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
                        <div class="pt-5 pt-lg-8 pl-md-5 pl-lg-9 space-bottom-2 space-bottom-lg-3 mb-xl-1">
                            <h6 class="font-weight-medium font-size-7 ml-lg-1 mb-lg-8 pb-xl-1">{{ __('web.Orders') }} @if(request()->type){{ __('web.'.ucfirst(request()->type)) }}@endif</h6>
                            @include('msg')
                            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('web.Date') }}</th>
                                        <th class="text-center">{{ __('web.Status') }}</th>
                                        <th>{{ __('web.Total') }}</th>
                                        <th class="text-center">{{ __('web.Payment Method') }}</th>
                                        <th class="text-right">{{ __('web.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="text-center"><span class="badge badge-{{ $status_color[$item->status] }}">{{ $status[$item->status] }}</span></td>
                                        <td>@money($item->total + $item->shipping + $item->gift_wrap - $item->coupon_discount)</td>
                                        <td class="text-center">{{ $item->payment }}</td>
                                        <td class="text-right"><a href="{{ url('my-account/order/'.$item->id) }}">{{ __('web.Show') }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($items->total()==0)
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __('web.No Order Found!') }}</td>
                                </tr>
                                @endif
                            </table>
                            <div class="justify-content-center text-center">
                                {{ $items->links() }}
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
<script>
    $('ul.pagination').addClass('justify-content-center');
</script>
@endpush