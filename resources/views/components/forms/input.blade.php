@php 
$id_name = preg_replace('/(.*)\[([a-z0-9_\-]+)\]/', '$1_$2', $name);
if($id_name==$name){
    $id_name = preg_replace('/(.*)\[\]/', '$1', $name);
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $id_name }}">
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label for="{{ $id_name }}">{{ $title }} {!! ($attributes->get('required'))?'<sup style="color:red;">*</sup>':'' !!}</label> 
        <input 
        class="form-control{{ ($attributes->get('class')) ? ' '.$attributes->get('class') :'' }}" 
        type="{{ $attributes->get('type', 'text') }}" 
        value="{{ (empty(old($name,$value)))? @$value: old($name,$value) }}" 
        {{ ($attributes->get('pattern'))?'pattern='.$attributes->get('pattern'):'' }}
        {{ ($attributes->get('step'))?'step='.$attributes->get('step',''): '' }}
        name="{{ $name }}" 
        id="{{ $id_name }}" {{ ($attributes->get('required'))?'required':'' }}  {{ ($disabled)? 'disabled' : '' }}
        placeholder="{{ $attributes->get('placeholder','Enter '.$title) }}" >
        @if ($errors->has($name))
        <span id="{{ $name }}-error" class="help-block">
            @if(@$msg)
            <strong>{{ $msg }}</strong>
            @else
            <strong>@lang($errors->first($name))</strong>
            @endif
         </span>
        @endif
    </div>
</div>