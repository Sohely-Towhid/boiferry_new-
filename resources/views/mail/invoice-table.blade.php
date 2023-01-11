@component('mail::message')
<div style="text-align: center;">
    @if(@$image)
    <img src="{{ $image }}" alt=""><br>
    @endif
    @if(@$h1)
    <center style="font-size: 25px; font-family: 'Poppins', Helvetica, Arial, sans-serif; font-weight: 700; color: black; margin-top: 10px; margin-bottom: 10px;">{{ $h1 }}</center>
    @endif
</div>

**{{ $greeting }}**

{!! implode("\n\n", $introLines) !!}
@if(@$invoice)
@component('mail::table')
| NAME       | RATE         | AMOUNT  |
|:------------------- |:-------:| --------:|
@foreach($invoice->metas as $meta)
| {{ $meta->product->title_bn }} x {{ $meta->quantity }}     | {{ $meta->rate }}      | @money($meta->rate * $meta->quantity)      |
@endforeach
|  | Sub Total | @money($invoice->total) |
|  | Shipping | @money($invoice->shipping) |
@if($invoice->gift_wrap>0)
|  | Gift Wrap | @money($invoice->gift_wrap) |
@endif
@if($invoice->coupon_discount>0)
|  | Coupon Discount | @money($invoice->coupon_discount) |
@endif
|  | Total | @money($invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount) |
@if($invoice->partial_payment>0 && $invoice->status < 4)
|  | Due | @money($invoice->total + $invoice->shipping + $invoice->gift_wrap - $invoice->coupon_discount - $invoice->partial_payment) |
@endif
@endcomponent
@endif
@component('mail::button', ['url' => @$url])
{{ $actionText }}
@endcomponent

{{ implode("\n\n", $outroLines) }}

Thank you so much,<br>
The "{{ config('app.name') }}" Team<br>
09638112112

@component('mail::subcopy')
If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endcomponent



