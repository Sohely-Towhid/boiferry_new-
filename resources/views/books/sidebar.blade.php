@php
$filter = ['year'=> b2e(request()->get('year', @$year)), 'month'=> request()->get('month',@$month)];
@endphp
<style>
    .show-filter{display: none;}
    .widget-area .widget{display: block;}
    @media only screen and (max-width: 600px){
        .show-filter{
            display: block;
            padding-bottom: 15px;
            text-align: center;
            margin-top: -15px;
        }
        .widget-area .widget{
            display: none;
        }
    }
</style>

<div id="widgetAccordion" class="widget-area">
    <div class="show-filter">
        <button class="btn btn-block btn-info border-1" type="button" onclick="$('.widget-area .widget').toggle();">Show/Hide Filter Options</button>
    </div>
    <div id="woocommerce_product_categories-2" class="widget p-4d875 border woocommerce widget_product_categories">
        <div id="wid_search" class="widget-head">
            <a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#wid_col_search" aria-expanded="true" aria-controls="wid_col_search">
                <h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Search') }}</h3>
                <svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
                </svg>
                <svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
                    <path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
                </svg>
            </a>
        </div>
        <div id="wid_col_search" class="mt-3 widget-content collapse show" aria-labelledby="wid_search" data-parent="#widgetAccordion">
            <form class="form-inline my-2 overflow-hidden">
                <div class="input-group flex-nowrap w-100">
                    <div class="input-group-prepend">
                        <i class="glph-icon flaticon-loupe py-2d75 bg-white-100 border-white-100 text-dark pl-3 pr-0 rounded-0"></i>
                    </div>
                    <input class="form-control bg-white-100 py-2d75 height-4 border-white-100 rounded-0" name="q" value="{{ request()->q }}" id="_search" type="search" placeholder="{{ __('web.Search') }}" aria-label="{{ __('web.Search') }}">
                </div>
                {{-- <button class="btn btn-outline-success my-2 my-sm-0 sr-only" type="submit">Search</button> --}}
            </form>
        </div>
    </div>


	<div id="woocommerce_product_categories-2" class="widget p-4d875 border woocommerce widget_product_categories">
		<div id="wid_category" class="widget-head">
			<a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#wid_col_category" aria-expanded="true" aria-controls="wid_col_category">
				<h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Subject') }}</h3>
				<svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
				</svg>
				<svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
				</svg>
			</a>
		</div>
		<div id="wid_col_category" class="mt-3 widget-content collapse show" aria-labelledby="wid_category" data-parent="#widgetAccordion">
			<form class="form-inline my-2 overflow-hidden">
				<div class="input-group flex-nowrap w-100">
					<div class="input-group-prepend">
						<i class="glph-icon flaticon-loupe py-2d75 bg-white-100 border-white-100 text-dark pl-3 pr-0 rounded-0"></i>
					</div>
					<input class="form-control bg-white-100 py-2d75 height-4 border-white-100 rounded-0" id="category" type="search" placeholder="{{ __('web.Search') }}" aria-label="{{ __('web.Search') }}">
				</div>
				{{-- <button class="btn btn-outline-success my-2 my-sm-0 sr-only" type="submit">Search</button> --}}
			</form>
			<ul class="product-categories"></ul>
		</div>
	</div>

	<div id="Authors" class="widget widget_search widget_author p-4d875 border">
		<div id="wid_author" class="widget-head">
			<a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#wid_col_author" aria-expanded="true" aria-controls="wid_col_author">
				<h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Author') }}</h3>
				<svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
				</svg>
				<svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
				</svg>
			</a>
		</div>
		<div id="wid_col_author" class="mt-4 widget-content collapse show" aria-labelledby="wid_author" data-parent="#widgetAccordion">
			<form class="form-inline my-2 overflow-hidden">
				<div class="input-group flex-nowrap w-100">
					<div class="input-group-prepend">
						<i class="glph-icon flaticon-loupe py-2d75 bg-white-100 border-white-100 text-dark pl-3 pr-0 rounded-0"></i>
					</div>
					<input class="form-control bg-white-100 py-2d75 height-4 border-white-100 rounded-0" id="author" type="search" placeholder="{{ __('web.Search') }}" aria-label="{{ __('web.Search') }}">
				</div>
				{{-- <button class="btn btn-outline-success my-2 my-sm-0 sr-only" type="submit">Search</button> --}}
			</form>
			<ul class="product-categories"></ul>
		</div>
	</div>

	<div id="Language" class="widget p-4d875 border">
		<div id="wid_lang" class="widget-head">
			<a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#wid_col_lang" aria-expanded="true" aria-controls="wid_col_lang">
				<h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Language') }}</h3>
				<svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
				</svg>
				<svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
				</svg>
			</a>
		</div>
		<div id="wid_col_lang" class="mt-4 widget-content collapse show" aria-labelledby="wid_lang" data-parent="#widgetAccordion">
			<ul class="product-categories">
				@php $language = ['বাংলা' => 'বাংলা','English' => 'English','Arabic' => 'Arabic','Hibru' => 'Hibru','Sanskrit' => 'Sanskrit','Hindi' => 'Hindi','Chinese' => 'Chinese','Japanese' => 'Japanese']; @endphp
				@foreach($language as $lang => $lang_name)
				<li class="cat-item cat-item-12"><a href="{{ url('books?').http_build_query($filter) }}&lang={{ $lang }}">{{ $lang_name }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
	<div id="Format" class="widget p-4d875 border">
		<div id="widgetHeading23" class="widget-head">
			<a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#widgetCollapse23" aria-expanded="true" aria-controls="widgetCollapse23">
				<h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Type') }}</h3>
				<svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
				</svg>
				<svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
				</svg>
			</a>
		</div>
		<div id="widgetCollapse23" class="mt-3 widget-content collapse show" aria-labelledby="widgetHeading23" data-parent="#widgetAccordion">

			<ul class="product-categories">
				@php $type = ['hardcover' => 'হার্ডকভার','paperback' => 'পেপারব্যাক','ebook' => 'ইবুক','audio-book' => 'অডিও বুক']; @endphp
				@foreach($type as $ty => $lang_name)
				<li class="cat-item cat-item-12"><a href="{{ url('books?').http_build_query($filter) }}&type={{ $ty }}">{{ $lang_name }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
	<div id="Review" class="widget p-4d875 border">
		<div id="wid_rev" class="widget-head">
			<a class="d-flex align-items-center justify-content-between text-dark" href="#" data-toggle="collapse" data-target="#wid_rev_col" aria-expanded="true" aria-controls="wid_rev_col">
				<h3 class="widget-title mb-0 font-weight-medium font-size-3">{{ __('web.Review') }}</h3>
				<svg class="mins" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="2px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M0.000,-0.000 L15.000,-0.000 L15.000,2.000 L0.000,2.000 L0.000,-0.000 Z"></path>
				</svg>
				<svg class="plus" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px">
					<path fill-rule="evenodd" fill="rgb(22, 22, 25)" d="M15.000,8.000 L9.000,8.000 L9.000,15.000 L7.000,15.000 L7.000,8.000 L0.000,8.000 L0.000,6.000 L7.000,6.000 L7.000,-0.000 L9.000,-0.000 L9.000,6.000 L15.000,6.000 L15.000,8.000 Z"></path>
				</svg>
			</a>
		</div>
		<div id="wid_rev_col" class="mt-4 widget-content collapse show" aria-labelledby="wid_rev" data-parent="#widgetAccordion">
			<ul class="product-categories">
				<li class="cat-item cat-item-12">
					<a href="{{ url('books?').http_build_query($filter) }}&rating=5">{!! htmlStar(5,'font-size-2 mr-1') !!}</a>
				</li>
				<li class="cat-item cat-item-12">
					<a href="{{ url('books?').http_build_query($filter) }}&rating=4">{!! htmlStar(4,'font-size-2 mr-1') !!}</a>
				</li>
				<li class="cat-item cat-item-12">
					<a href="{{ url('books?').http_build_query($filter) }}&rating=3">{!! htmlStar(3,'font-size-2 mr-1') !!}</a>
				</li>
				<li class="cat-item cat-item-12">
					<a href="{{ url('books?').http_build_query($filter) }}&rating=2">{!! htmlStar(2,'font-size-2 mr-1') !!}</a>
				</li>
				<li class="cat-item cat-item-12">
					<a href="{{ url('books?').http_build_query($filter) }}&rating=1">{!! htmlStar(1,'font-size-2 mr-1') !!}</a>
				</li>
			</ul>
			
		</div>
	</div>
</div>


@push('scripts')
<script>
function instantCatSearch(){
	var term = $("#category").val();
	if(term){
		$.ajax({
			method: "GET",
			url: "{{ url('ajax/category') }}?q=" + term,
			success: function(data){
				var html = '';
				$.each(data.results, function(i,v){
					html += '<li class="cat-item cat-item-12"><a href="{{ url('/category') }}/'+ v.slug +'?{{ http_build_query($filter) }}">' + v.text + '</a></li>';
				});
				$("#wid_col_category ul").html(html);
			},error: function(data){
		
			}
		});
	}
}

$("#category").on('keyup change', instantCatSearch);

function instantAutSearch(){
	var term = $("#author").val();
	if(term){
		$.ajax({
			method: "GET",
			url: "{{ url('ajax/author') }}?q=" + term,
			success: function(data){
				var html = '';
				$.each(data.results, function(i,v){
					html += '<li class="cat-item cat-item-12"><a href="{{ url('/author') }}/'+ v.slug +'?{{ http_build_query($filter) }}">' + v.text + '</a></li>';
				});
				$("#wid_col_author ul").html(html);
			},error: function(data){
		
			}
		});
	}
}

$("#author").on('keyup change', instantAutSearch);
</script>
@endpush