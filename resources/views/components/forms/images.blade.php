@php
$required = ($attributes->get('required')) ?'required':'';
if($value && $required == 'required'){
    $required = '';
}
$value = (empty(old($name,$value)))? @$value: old($name,$value);
if(!is_array($value)){
    $value = array_filter(explode(",", $value));
}
@endphp
<style>
#_images_ img {width: 90px;border: 4px solid #00a9f4;margin-top: 5px;padding: 5px;margin-bottom: 15px;}#_images_ img:hover {width: 90px;border: 4px solid red;margin-top: 5px;padding: 5px;}#_images_ button {position: absolute;left: 0;top: 180px;text-align: center;opacity: 0;}#_images_ img:hover+button, #_images_ button:hover {opacity: 1;width: 100px;margin: auto;left: 40px;top: 105px;background: #ff0c00f7;border: 0;color: white;height: 30px;border-radius: 5px;}#_images_ div {float: left;position: relative;width: 90px;margin-right: 5px;}[class*='close-'] {color: #ffffff;font: 14px/100% arial, sans-serif;position: absolute;right: 0px;text-decoration: none;text-shadow: 0 1px 0 #fff;top: 5px;background: red;padding: 5px;}.close-thik:after {content: 'X';}
</style>
<div class="col-md-{{ $column }}" id="col_{{ $name }}">
    <input type="hidden" name="{{ $name }}" id="{{ $name }}" {{ $required }}>
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label>Image Gallery ({{ $attributes->get('size', '1200*885') }}) {!! ($attributes->get('required'))?' <sup style="color:red;">*</sup>':'' !!}</label>
        <div></div>
        <div class="custom-file">
            <input type="file" class="custom-file-input"  name="_images" id="_images" onchange="uploadImage()" accept="image/*"/>
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        <div id="_images_"></div>
        @if ($errors->has($name))
        <span class="help-block">
            @if(@$msg)
            <strong>{{ $msg }}</strong>
            @else
            <strong>@lang($errors->first($name))</strong>
            @endif
         </span>
        @endif
    </div>
</div>
@push('_scripts')
window.img_list = [];
@if($value)
@foreach($value as $img)
window.img_list.push({thumb: '{{ asset(str_replace("redactor/", "assets/images/redactor/sm_", $img)) }}', url:'{{ $img }}'});
@endforeach
$("#_images_").html('');
$("#imgs").html('');
var img = [];
$.each(window.img_list,function(i,v){
    $("#_images_").append('<div><img src="' + v.thumb + '"><a href="#" class="close-thik" onclick="deleteProductImage(\''+ i +'\')"></a></div>');
    img.push(v.url);
    $("#imgs").append('<option value="'+v.url+'">');
});
$("#images").val(img.join(','));
@endif

function uploadImage(){
    var formData = new FormData();
    formData.append('file[0]', $('#_images')[0].files[0]);
    @if($attributes->get('extra'))
    @php $__data = explode("=", $attributes->get('extra')); @endphp
    formData.append('{{ $__data[0] }}', '{{ $__data[1] }}');
    @endif
    $.ajax({
        url : '{{ url('redactor-image') }}',
        type : 'POST',
        data : formData,
        processData: false,
        contentType: false,
        success : function(data) {
            $.each(data, function(i,v){
                window.img_list.push(v);
            });
            $("#_images_").html('');
            $("#imgs").html('');
            var img = [];
            $.each(window.img_list,function(i,v){
                $("#_images_").append('<div><img src="' + v.thumb + '"><a href="#" class="close-thik" onclick="deleteProductImage(\''+ i +'\')"></a></div>');
                img.push(v.url);
                $("#imgs").append('<option value="'+v.url+'">');
            });
            $("#images").val(img.join(','));
        }
    });
}

function deleteProductImage(i){
    window.img_list.splice(i,1);
    $("#_images_").html('');
    $("#imgs").html('');
    var img = [];
    $.each(window.img_list,function(i,v){
        img.push(v.url);
        $("#imgs").append('<option value="'+v.url+'">');
        $("#_images_").append('<div><img src="' + v.thumb + '"><a href="#" class="close-thik" onclick="deleteProductImage(\''+ i +'\')"></a></div>');
    });
    $("#images").val(img.join(','));
}
@endpush

    
