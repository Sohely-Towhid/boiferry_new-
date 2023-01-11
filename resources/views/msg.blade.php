@if ($errors->any())
    <?php $error = $errors->first(); ?>
@endif
@if(isset($success))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ $success }}
</div>
@elseif(isset($error))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ $error }}
</div>
@elseif(isset($info))
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ $info }}
</div>
@elseif(isset($warning))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ $warning }}
</div>
@endif


@if(Session::get('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('success') }}
</div>
@elseif(Session::get('error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('error') }}
</div>
@elseif(Session::get('info'))
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('info') }}
</div>
@elseif(Session::get('warning'))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('warning') }}
</div>
@endif