@php
$required = ($attributes->get('required')) ?'required':'';
$value = (empty(old($name,$value)))? @$value: old($name,$value);
if($value && $required == 'required'){
    $required = '';
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $name }}">
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label>{{ $title }} ({{ $attributes->get('size', '1200*885') }}) {!! ($attributes->get('required'))?' <sup style="color:red;">*</sup>':'' !!}</label>
        <div></div>
        <div class="custom-file">
            @if(!empty($value))
            <input class="custom-file-input" type="file" name="{{ $name }}" id="{{ $name }}" onchange="loadFile_{{ $name }}(event)" accept="image/*">
            <label class="custom-file-label" for="{{ $name }}">Choose file</label>
            @else
            <input class="custom-file-input" type="file" name="{{ $name }}" id="{{ $name }}" onchange="loadFile_{{ $name }}(event)" accept="image/*" {{ $required }}>
            <label class="custom-file-label" for="{{ $name }}">Choose file</label>
            @endif
        </div>
        @if(!empty($value))
        <img id="output_{{ $name }}" src="{{ showImage($value) }}" alt="" style="width: {{ $attributes->get('width', '200px') }}; padding-top: 10px;">
        @else
        <img id="output_{{ $name }}" src="{{ url('assets/images/default-image.jpg') }}" alt="" style="width: {{ $attributes->get('width', '200px') }}; padding-top: 10px;">
        @endif
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
var loadFile_{{ $name }} = function(event) {
    var output = document.getElementById('output_{{ $name }}');
    output.src = URL.createObjectURL(event.target.files[0]);
};
@endpush