@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.files.show', $dataset, $file))

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}}
            <a class="action-link float-right"
               href="{{route('public.datasets.download_file', [$dataset, $file])}}">
                <i class="fas fa-download mr-2"></i>Download File
            </a>
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $file])
                <span class="ml-4">Mediatype: {{$file->mime_type}}</span>
            @endcomponent
            <hr>
            <br>

            @include('public.datasets.files.display-file')
        @endslot
    @endcomponent
@endsection
