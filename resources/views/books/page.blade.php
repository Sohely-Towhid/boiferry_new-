@extends('layouts.books')
@section('title',$item->name)
@include('web-seo',['item', $item])
@section('content')

<main id="main" class="site-main mb-10">
    <div class="container">
        <div class="py-4 py-lg-5 py-xl-8">
            <h6 class="font-weight-medium font-size-7 font-size-xs-25 text-center">{{ $item->name }}</h6>
        </div>
        <div class="mb-5">
            @if($item->slug=='support')
            <div class="col-lg-8 mx-auto">
                {!! $item->description !!}
                @include('page.support')
            </div>
            @elseif(preg_match("/{books:([0-9,]+)}/", $item->description, $match))
                @php $books = explode(',',$match[1]); $books = App\Models\Book::whereIn('id', $books)->get(); @endphp
                <section class="space-bottom-2 space-bottom-lg-3 archive">
                @include('books.joined_multi_line', ['_title'=> '', '_link'=>false, '_loop'=> $books, '_show'=> 6, '_bg'=> '', '_section'=>'space-bootom-1'])
                </section>
            @else
            {!! $item->description !!}
            @endif
        </div>
    </div>  
</main>
@endsection

@push('scripts')
@endpush