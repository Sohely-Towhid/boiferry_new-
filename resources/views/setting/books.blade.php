{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','Books Setting')
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">Books Setting</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">Home Page Setting for Books Site</span>
                </h3>
                <div class="card-toolbar">
                </div>
            </div>

            <div class="card-body pt-6">
                <form action="{{ url('setting/books') }}" method="POST" enctype="multipart/form-data">
                @php
                $theme = [
                    'joined_single_line' => 'Joined Single Line (Scrolling)',
                    'joined_multi_line' => 'Joined Multi Line',
                    'boxed_single_line' => 'Boxed Single Line (Scrolling)',
                    'boxed_multi_line' => 'Boxed Multi Line',
                ];
                $bg = ['bg-gray-200','bg-dark','bg-light','bg-light-gray','bg-primary-green','bg-primary-yellow','bg-yellow-darker','bg-primary-indigo','bg-indigo-light','bg-tangerine','bg-tangerine-light','bg-chili','bg-chili-light','bg-carolina','bg-carolina-light'];
                $js = [];
                $timeout = 0;
                @endphp
                <style>
                    .select2-container--bootstrap{
                        width: 100% !important
                    }
                </style>
                <!-- form start -->
                <div class="row">
                    @csrf
                    <div class="col-md-12">@include('msg')</div>
                    <x-form::input column="3" name="book_home_slider" title="Number of Slider in Home" :required="true" type="text" value="{{ @$items->book_home_slider->value }}" />
                    <x-form::input column="3" name="book_home_shipping" title="Shipping Cost" :required="true" type="number" value="{{ @$items->book_home_shipping->value }}" />
                    <x-form::input column="3" name="book_home_shipping_cod" title="Shipping Cost (COD)" :required="true" type="number" value="{{ @$items->book_home_shipping_cod->value }}" />
                    <x-form::input column="3" name="book_home_shipping_out" title="Shipping Cost(Outside Dhaka)" :required="true" type="number" value="{{ @$items->book_home_shipping_out->value }}" />
                    <x-form::input column="3" name="book_home_shipping_out_cod" title="Shipping Cost(Outside Dhaka - COD)" :required="true" type="number" value="{{ @$items->book_home_shipping_out_cod->value }}" />
                    <x-form::input column="3" name="book_home_free_shipping" title="Free Shipping Over" :required="true" type="number" value="{{ @$items->book_home_free_shipping->value }}" />
                    <x-form::input column="3" name="book_home_gift_wrap" title="Gift Wrap Cost" :required="true" type="number" value="{{ @$items->book_home_gift_wrap->value }}" />
                    
                    <div class="col-md-12 mb-6">
                        <label for="">Discount Banner Setting</label>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">bKash</label>
                                <input type="number" max="100" step="0.01" name="book_home_extra_discount[bkash]" value="{{ @$items->book_home_extra_discount->value->bkash }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="">Nagad</label>
                                <input type="number" max="100" step="0.01" name="book_home_extra_discount[nagad]" value="{{ @$items->book_home_extra_discount->value->nagad }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="">SSL</label>
                                <input type="number" max="100" step="0.01" name="book_home_extra_discount[ssl]" value="{{ @$items->book_home_extra_discount->value->ssl }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="100px">Ad Status</th>
                                    <th>Top Ad Link</th>
                                    <th width="200px">Ad Image</th>
                                    <th>Ad Preview</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="switch">
                                            <label>
                                                <input name="book_home_top_ad[status]" type="checkbox" {{ (@$items->book_home_top_ad->value->status) ? 'checked' : '' }} name="select"/>
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td><input type="text" name="book_home_top_ad[link]" value="{{ @$items->book_home_top_ad->value->link }}" class="form-control"></td>
                                    <td><input type="file" name="book_home_top_ad[image]" class=""></td>
                                    <td>
                                        @if(@$items->book_home_top_ad->value->image)
                                        <img src="{{ showImage($items->book_home_top_ad->value->image) }}" width="100%">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            Title Rule: English|বাংলা
                        </div>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Data Type</th>
                                    <th>Time Period</th>
                                    <th>Background</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($j=1; $j<=6; $j++)
                                @php
                                $dy_ver = 'book_home_fixed_'.$j;
                                $se = @$items->$dy_ver->value;
                                $fixed_js[] = '$("#data_type_'.$j.'").val("'.$se->data_type.'").change()';
                                $fixed_js[] = '$("#time_period_'.$j.'").val("'.$se->time_period.'").change()';
                                $fixed_js[] = '$("#fixed_bg_'.$j.'").val("'.$se->bg.'").change()';
                                $fixed_js[] = '$("#position_'.$j.'").val("'.$se->position.'").change()';
                                @endphp
                                <tr>
                                    <td>
                                        <span class="switch">
                                            <label>
                                                <input name="fixed_status[{{ $j }}]" type="checkbox" {{ (@$items->$dy_ver->status) ? 'checked' : '' }} name="select"/>
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td><input type="text" name="name[{{ $j }}]" value="{{ $se->name }}" class="form-control"></td>
                                    <td>
                                        <select name="position[{{ $j }}]" id="position_{{ $j }}" value="{{ $se->position }}" class="form-control">
                                            <option value="top">Top</option>
                                            <option value="bottom">Bottom</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="data_type[{{ $j }}]" id="data_type_{{ $j }}" value="{{ $se->data_type }}" class="form-control">
                                            <option value="best_seller_books">Best Seller Books</option>
                                            <option value="best_seller_author">Best Seller Author</option>
                                            <option value="new_published_books">New Books</option>
                                            <option value="last_sold_books">Last Sold Books</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="time_period[{{ $j }}]" id="time_period_{{ $j }}" value="{{ $se->time_period }}" class="form-control">
                                            <option value="day">Today</option>
                                            <option value="week">This Week</option>
                                            <option value="last_7">Last 7 Days</option>
                                            <option value="month">This Month</option>
                                            <option value="last_30">Last 30 Days</option>
                                            <option value="year">This Year</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select type="text" name="fixed_bg[{{ $j }}]" id="fixed_bg_{{ $j }}" value="{{ @$se->bg }}" class="form-control">
                                            <option value=""></option>
                                            @foreach($bg as $_bg)
                                            <option value="{{ $_bg }}">{{ $_bg }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save Setting</button>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="50">Status</th>
                                        <th width="200">Title</th>
                                        <th>Category | Author</th>
                                        <th width="100">Take Items</th>
                                        <th width="100">Show Items</th>
                                        <th width="150">BG</th>
                                        <th width="150">Theme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i=1; $i<=20; $i++)
                                    @php
                                    $dy_ver = 'book_home_block_'.$i;
                                    $se = $items->$dy_ver->value;
                                    $js[] = '$("#bg_'.$i.'").val("'.$se->bg.'");';
                                    $js[] = '$("#theme_'.$i.'").val("'.$se->theme.'");';
                                    if($se->category){
                                        $cats = App\Models\Category::whereIn('id',$se->category)->get(['id','name'])->toArray();
                                        $html = array_map(function($cat){
                                            return '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
                                        }, $cats);
                                        $js[] = '$("#cat_'.$i.'").empty().append(\''.str_replace("'", "\'", implode('',$html)).'\').val('.json_encode($se->category).').trigger(\'change\')';
                                    }
                                    if(@$se->author){
                                        $cats = App\Models\Author::whereIn('id',$se->author)->get(['id','name'])->toArray();
                                        $html = array_map(function($cat){
                                            return '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
                                        }, $cats);
                                        $js[] = '$("#aut_'.$i.'").empty().append(\''.str_replace("'", "\'", implode('',$html)).'\').val('.json_encode($se->author).').trigger(\'change\')';
                                    }
                                    if(@$se->opt_category){
                                        $cats = App\Models\Category::whereIn('id',$se->opt_category)->get(['id','name'])->toArray();
                                        $html = array_map(function($cat){
                                            return '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
                                        }, $cats);
                                        $js[] = '$("#opt_cat_'.$i.'").empty().append(\''.str_replace("'", "\'", implode('',$html)).'\').val('.json_encode($se->opt_category).').trigger(\'change\')';
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td class="text-center">
                                            <span class="switch">
                                                <label>
                                                    <input name="status[{{ $i }}]" type="checkbox" {{ ($items->$dy_ver->status) ? 'checked' : '' }} name="select"/>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </td>
                                        <td><input type="text" name="title[{{ $i }}]" value="{{ $se->title }}" class="form-control"></td>
                                        <td>
                                            <div class="from-group">
                                                <select id="cat_{{ $i }}" name="category[{{ $i }}][]" class="form-control" multiple="multiple">
                                                    <option value="x">Default</option>
                                                </select>
                                            </div>

                                            <div class="from-group mt-2">
                                                <select id="aut_{{ $i }}" name="author[{{ $i }}][]" class="form-control" multiple="multiple">
                                                    <option value="x">Default</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td><input type="number" name="take_items[{{ $i }}]" value="{{ $se->take_items }}" class="form-control"></td>
                                        <td><input type="number" name="show_items[{{ $i }}]" value="{{ $se->show_items }}" class="form-control"></td>
                                        <td>
                                            <select type="text" name="bg[{{ $i }}]" id="bg_{{ $i }}" value="{{ $se->bg }}" class="form-control">
                                                <option value=""></option>
                                                @foreach($bg as $_bg)
                                                <option value="{{ $_bg }}">{{ $_bg }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select type="text" name="theme[{{ $i }}]" id="theme_{{ $i }}" value="{{ $se->theme }}" class="form-control">
                                                <option value=""></option>
                                                @foreach($theme as $t_k => $t)
                                                <option value="{{ $t_k }}">{{ $t }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%">
                                            <select id="opt_cat_{{ $i }}" type="text" name="opt_category[{{ $i }}][]" class="form-control" multiple="multiple" placeholder="Extra Category">
                                                <option value="x">Default</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save Setting</button>
                    </div>
                </div>
                <!-- form end -->

                @push('scripts')
                <script>
                @for($i=1; $i<=20; $i++)
                var cat_{{ $i }} = $("#cat_{{ $i }}").select2({theme: "bootstrap",ajax: {tags: true,delay: 200,url: '{{ url('book/category/select') }}',processResults: function(data) {return {results: data.results};},cache: false}});
                var aut_{{ $i }} = $("#aut_{{ $i }}").select2({theme: "bootstrap", placeholder: 'Author...', ajax: {tags: true,delay: 200,url: '{{ url('author/select?id=yes') }}',processResults: function(data) {return {results: data.results};},cache: false}});
                var opt_cat_{{ $i }} = $("#opt_cat_{{ $i }}").select2({placeholder: 'Extra Category', maximumSelectionLength: 5, theme: "bootstrap",ajax: {tags: true,delay: 200,minimumInputLength: 2,url: '{{ url('book/category/select') }}',processResults: function(data) {return {results: data.results};},cache: false}});
                @endfor

                function select2_search($el, term) {
                    if(Array.isArray(term)){
                        $.each(term, function(i,v){
                            setTimeout(function(){
                                select2_search($el, 'id:' + v);
                            }, i * 600);
                        });
                    }else{
                        $el.select2('open');
                        var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
                        $search.val(term);
                        $search.trigger('keyup');
                        setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
                    }
                }

                {!! implode("\n", $js) !!}

                </script>
                @endpush

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(@$fixed_js)
    {!! implode("\n", $fixed_js) !!}
    @endif
</script>
@endpush