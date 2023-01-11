{{-- BTL Template - Do not delete --}}
@php
$empty = [];
$color = ['Not Specified','Black','Red','White','Blue', 'Gold', 'Random'];
$size = ['Not Specified','XXL','XXXL','XL','M','S'];
@endphp

<!-- form start -->
<div class="row">
    @csrf
    <div class="col-md-12">@include('msg')</div>
    <x-form::input column="6" name="name" title="Product Name" :required="true" type="text" value="{{ @$item->name }}" />
    <x-form::select column="3" name="category_id" title="Category" :required="true" value="{{ @$item->category_id }}" :options="$empty" />
    <x-form::select column="3" name="brand_id" title="Brand" :required="false" value="{{ @$item->brand_id }}" :options="$empty" />
    <x-form::textarea column="12" name="short_description" title="Short Description" :required="true" value="{{ @$item->short_description }}" />
    <x-form::textarea column="12" name="description" title="Description" :required="true" type="text" value="{{ @$item->description }}" />
    <x-form::input column="4" name="sku" title="SKU" :required="true" type="text" value="{{ @$item->sku }}" />
    <x-form::input column="4" name="point" title="Store Point (1 Point = 1 TK)" :required="false" type="number" value="{{ @$item->point }}" />
    <x-form::input column="4" name="shelf" title="Shelf (Storage Box)" :required="false" type="text" value="{{ @$item->shelf }}" />
    <x-form::images column="4" name="images" title="Image Gallery" size="1200 x 1200px" :required="true" type="text" :value="@$item->images" />
    <div class="col-md-12">
        <h2>Price & Stock</h2>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Size <sup style="color:red;">*</sup></th>
                        <th>Color <sup style="color:red;">*</sup></th>
                        <th>Rate <sup style="color:red;">*</sup></th>
                        <th>Sale <sup style="color:red;">*</sup></th>
                        <th>Stock <sup style="color:red;">*</sup></th>
                    </tr>
                </thead>
                <tbody id="items">
                    @if(isset($items))
                    @foreach($items as $__key => $product)
                    <tr id="tr_{{ $__key }}">
                        <td>
                            <input type="hidden" name="pid[]" value="{{ $product->id }}">
                            <input type="text" class="form-control" name="size[]" list="sizes" value="{{ $product->size }}" required>
                        </td>
                        <td><input type="text" class="form-control" name="color[]" list="colors" value="{{ $product->color }}" required></td>
                        <td><input type="number" step="0.01" class="form-control" name="rate[]" value="{{ $product->rate }}" required></td>
                        <td><input type="number" step="0.01" class="form-control" name="sale[]" value="{{ $product->sale }}" required></td>
                        <td><div class="input-group"><input type="number" class="form-control" name="stock[]" value="{{ $product->stock }}" required></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <button type="button" onclick="addNewPS();" class="btn btn-info">+</button>
        <hr>
    </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">{{ (@$button) ? $button: 'Save Data' }}</button>
    </div>


    <datalist id="colors">
        @foreach($color as $_color)
        <option value="{{ $_color }}">
        @endforeach
    </datalist>

    <datalist id="sizes">
        @foreach($size as $_size)
        <option value="{{ $_size }}">
        @endforeach
    </datalist>
</div>
<!-- form end -->

@push('scripts')
<script>
    window._total = {{ isset($items) ? count($items) : 0 }};
    function addNewPS(){
        window._total += 1;
        var html = "";
        html += '<tr id="tr_'+window._total+'">';
        html += '    <td><input type="text" class="form-control" name="size[]" list="sizes" required></td>';
        html += '    <td><input type="text" class="form-control" name="color[]" list="colors" required></td>';
        html += '    <td><input type="number" step="0.01" class="form-control" name="rate[]" required></td>';
        html += '    <td><input type="number" step="0.01" class="form-control" name="sale[]" required></td>';
        html += '    <td><div class="input-group"><input type="number" class="form-control" name="stock[]" required><div class="input-group-append"><button class="btn btn-danger" type="button" onclick="$(\'#tr_'+ window._total +'\').remove();">X</button></div></div></td>';
        html += '</tr>';
        $('#items').append(html);
    }

    var category = $("#category_id").select2({
        theme: "bootstrap",
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('/product/category/select') }}',
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: false
        }
    });

    var brand = $("#brand_id").select2({
        theme: "bootstrap",
        ajax: {
            delay: 200,
            minimumInputLength: 2,
            url: '{{ url('/brand/select') }}',
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: false
        }
    });

    function select2_search($el, term) {
        $el.select2('open');
        var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
        $search.val(term);
        $search.trigger('keyup');
        setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
    }

    @if(old('category_id'))
    select2_search(category, "id:{{ old('category_id') }}");
    @endif

    @if(old('brand_id'))
    select2_search(brand, "id:{{ old('brand_id') }}");
    @endif

    @if(isset($item))
    select2_search(category, "id:{{ $item->category_id }}");
    select2_search(brand, "id:{{ $item->brand_id }}");
    @else
    addNewPS();
    @endif
</script>
@endpush
