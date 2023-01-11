@php 
$id_name = preg_replace('/(.*)\[([a-z0-9_\-]+)\]/', '$1_$2', $name);
if($id_name==$name){
    $id_name = preg_replace('/(.*)\[\]/', '$1', $name);
}
@endphp
<div class="col-md-{{ $column }}" id="col_{{ $id_name }}">
    <div class="form-group {{ ($errors->has($name)) ? 'has-error' :'' }}">
        <label for="{{ $id_name }}">{{ $title }} {!! (@$required)?'<sup style="color:red;">*</sup>':'' !!}</label>
        @if(isset($implode))
        <textarea name="{{ $name }}" style="height:{{ $attributes->get('height','100px') }};" id="{{ $id_name }}" class="form-control">{!! (empty(old($name,$value)))? @$value: old($name,$value) !!}</textarea>
        @else
        <textarea name="{{ $name }}" style="height:{{ $attributes->get('height','100px') }};" id="{{ $id_name }}" class="form-control">{!! (empty(old($name,$value)))? @$value: old($name,$value) !!}</textarea>
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

@push('scripts')
<script src="{{ url('redactor/redactor.js') }}"></script>
<script src="{{ url('redactor/redactor/inlinestyle.min.js') }}"></script>
<script src="{{ url('redactor/redactor/imagemanager.min.js') }}"></script>
<script src="{{ url('redactor/redactor/alignment.min.js') }}"></script>
<script src="{{ url('redactor/redactor/table.min.js') }}"></script>
<script src="{{ url('redactor/redactor/video.min.js') }}"></script>
<script src="{{ url('redactor/redactor/fullscreen.min.js') }}"></script>
<script src="{{ url('redactor/redactor/counter.min.js') }}"></script>
<script src="{{ url('redactor/redactor/fontfamily.js') }}"></script>
<script src="{{ url('redactor/redactor/fontsize.min.js') }}"></script>
<script src="{{ url('redactor/redactor/fontcolor.js') }}"></script>
<script src="{{ url('redactor/redactor/widget.min.js') }}"></script>
<script src="{{ url('redactor/redactor/tweets.js') }}"></script>
<script src="{{ url('redactor/redactor/textdirection.js') }}"></script>
<script src="{{ url('redactor/redactor/properties.js') }}"></script>
@endpush

@push('_scripts')
$(document).ready(function() {
    $('#{{ $name }}').redactor({
        minHeight: '300px',
        maxHeight: '500px',
        plugins: [
            'alignment',
            'inlinestyle',
            'fontfamily',
            'fontsize',
            'fontcolor',
            'table',
            'video',
            'widget',
            'imagemanager',
            'fullscreen',
            'counter',
            'tweets',
            'textdirection',
            'properties'
        ],
        imageUpload: '{{ url('redactor-image?type=rta') }}',
        imageManagerJson: '{{ url('assets/images/redactor/images.json') }}'
    });         
});
@endpush

@push('styles')
<link rel="stylesheet" href="{{ url('redactor/redactor.css') }}"/>
<style type="text/css">
#redactor-modal {z-index: 200000;}
</style>
@endpush