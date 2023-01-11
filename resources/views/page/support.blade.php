<h3 class="text-center mt-5">Need more help? Let us know.</h3>
@include('msg')
<form action="{{ url('/support') }}" method="POST" class="mt-5">
    <div class="row">
        @csrf
        <x-form::input column="6" name="name" title="Name" :required="true" type="text" value="{{ old('name') }}" />
        <x-form::input column="6" name="email" title="Email" :required="true" type="email" value="{{ old('email') }}" />
        <x-form::input column="12" name="subject" title="Subject" :required="true" type="text" value="{{ old('subject') }}" />
        <x-form::textarea column="12" name="details" title="Details please!" :required="true" value="{{ old('details') }}" />
        <div class="col-12 d-flex justify-content-lg-start">
            {!! captcha_img('flat') !!}
            <input type="text" name="captcha" class="ml-3 form-control" style="width: 200px;" placeholder="Type Captcha" required>
        </div>
        <div class="col d-flex justify-content-lg-start mt-3">
            <button type="submit" class="btn btn-wide btn-dark text-white rounded-0 transition-3d-hover height-60">Sumbit Message</button>
        </div>
    </div>
</form>