@php 
$id_name = preg_replace('/(.*)\[([a-z0-9_\-]+)\]/', '$1_$2', $name);
if($id_name==$name){
    $id_name = preg_replace('/(.*)\[\]/', '$1', $name);
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $id_name }}">
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label for="{{ $name }}">{{ $title }} {!! ($attributes->get('required'))?'<sup style="color:red;">*</sup>':'' !!}</label> 
        <select 
        class="form-control{{ ($attributes->get('class')) ? ' '.$attributes->get('class') :'' }}" 
        type="{{ $attributes->get('type', 'text') }}" 
        {{ ($attributes->get('multiple')) ? 'multiple="multiple"' : '' }}
        value="{{ (empty(old($name,$value)))? @$value: old($name,$value) }}" 
        name="{{ $name }}" 
        id="{{ $id_name }}" {{ ($attributes->get('required'))?'required':'' }} 
        placeholder="{{ $attributes->get('placeholder','Select Option') }}" >
            @if(!$attributes->get('select2'))
            <option value="">{{ $attributes->get('placeholder') }}</option>
            @endif
            @if($options instanceof Illuminate\Database\Eloquent\Collection)
            @foreach($options as $key => $o)
            <option value="{{ $o->{$attributes->get('select-value')} }}">{{ $o->{$attributes->get('select-title')} }}</option>
            @endforeach
            @else
            @if($options)
            @foreach($options as $key => $o)
            <option value="{{ $key }}">{{ $o }}</option>
            @endforeach
            @endif
            @endif
        </select>
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
$("#{{ $id_name }}").val("{!! (empty(old($name,$value)))? @$value: old($name,$value) !!}").trigger('change');
@endpush