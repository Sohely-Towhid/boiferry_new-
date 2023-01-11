@php
$required = ($attributes->get('required')) ?'required':'';
$value = (empty(old($name,$value)))? @$value: old($name,$value);
if($value && $required == 'required'){
    $required = '';
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $name }}">
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label for="{{ $name }}">{{ $title }} {!! ($attributes->get('required'))?' <sup style="color:red;">*</sup>':'' !!} @if(!empty($value))<span class="badge badge-success">✓</span>@endif</label>
        <div></div>
        <div class="custom-file">
        <label class="custom-file-label">File Browser</label>
        @if(!empty($value))
        <input class="custom-file-input" type="file" name="{{ $name }}" id="{{ $name }}" onchange="loadFile_{{ $name }}(event)">
        @else
        <input class="custom-file-input" type="file" name="{{ $name }}" id="{{ $name }}" onchange="loadFile_{{ $name }}(event)" {{ $required }}>
        @endif
        @if($attributes->get('delete') && !empty($value))
        <a href="javascript:{};" onclick="$('#delete_{{ $name }}').val('yes');" class="text-danger">x Delete</a>
        <input type="hidden" name="delete_{{ $name }}" id="delete_{{ $name }}" value="no">
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
</div>

@push('_scripts')
@endpush