<div class="col-md-12 mt-3">
    <h3>SEO Setting (Optional)</h3>
    <div class="separator separator-dashed my-5"></div>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="keywords">Meta Keywords</label>
                        <select class="form-control" name="keywords[]" id="keywords" multiple>
                        </select>
                    </div>
                </div>
                <x-form::input column="12" name="og_image" title="OG Image" :required="false" type="text" value="{{ @$item->seo->og_image }}" />
                <x-form::textarea column="12" name="meta_description" title="Meta Description" :required="false" value="{{ @$item->seo->meta_description }}" />
            </div>
        </div>
        <x-form::image column="3" name="og_image_file" title="OG Image" size="1200x630px" :required="false" type="text" value="{{ @$item->seo->og_image }}" />
    </div>
</div>

@push('_scripts')
$("#keywords").select2({
    data: {!! json_encode(@$item->seo->keywords) !!},
    tags: true,
    multiple: true,
    tokenSeparators: [',', '|']
});
$('#keywords').val({!! json_encode(@$item->seo->keywords) !!}).change();
@endpush