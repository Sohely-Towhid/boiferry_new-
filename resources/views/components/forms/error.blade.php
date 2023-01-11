@if ($errors->has($name))
    <span class="help-block">
        @if(@$msg)
        <strong>{{ $msg }}</strong>
        @else
        <strong>@lang($errors->first($name))</strong>
        @endif
     </span>
@endif