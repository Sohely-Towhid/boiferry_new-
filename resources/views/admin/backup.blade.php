{{-- BTL Template - Do not delete --}}
@extends('layouts.admin')
@section('title','System Backup')
@section('content')
@php
$backup = Artisan::call('backup:list');
$backup = Artisan::output();
$items = [];
$lines = explode("\n",$backup);
foreach ($lines as $key => $line) {
    $chunk = explode('|', $line);
    if(count($chunk)>=8){
        $chunk = array_map('trim', $chunk);
        $chunk = array_values(array_filter($chunk));
        $items[] = $chunk;
    }
}
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-6">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder font-size-h4 text-dark-75">System Backup</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-lg">System Backup Status</span>
                </h3>
                <div class="card-toolbar">
                    <a href="?backup=yes" class="btn btn-fixed-height btn-primary font-weight-bolder font-size-sm px-5 my-1">Backup Now </a>
                </div>
            </div>

            <div class="card-body pt-6">
                 @include('msg')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach($items[0] as $item)
                            <th>{{ $item }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        @if($item[0]!='Name')
                        <tr>
                            @foreach($item as $_item)
                            <td>{{ $_item }}</td>
                            @endforeach
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush