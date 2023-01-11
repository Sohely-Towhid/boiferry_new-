@php 
$id_name = preg_replace('/(.*)\[([a-z0-9_\-]+)\]/', '$1_$2', $name);
if($id_name==$name){
    $id_name = preg_replace('/(.*)\[\]/', '$1', $name);
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $id_name }}">
    <div class="form-group form-check {{ ($errors->has($name)) ? 'has-error' :'' }}" style="padding-left: 30px;">    
        <input type="checkbox" name="{{ $name }}" id="{{ $id_name }}" {{ ($attributes->get('required'))?'required':'' }} value="{{ (empty(old($name,$value)))? @$value: old($name,$value) }}" class="form-check-input">
        <label class="form-check-label" for="{{ $name }}">{{ $title }} {!! ($attributes->get('required'))?'<sup style="color:red;">*</sup>':'' !!}</label>
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