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
            <x-show-standard-details :item="$file">
                <span class="ml-3 fs-9 grey-5">Mediatype: {{$file->mime_type}}</span>
                <span class="ml-3 fs-9 grey-5">Size: {{$file->toHumanBytes()}}</span>
            </x-show-standard-details>

            <hr>
            <br>
            @include('partials.files._display-file', [
                'displayRoute' => route('public.datasets.files.display', [$dataset, $file])
            ])
        @endslot
    @endcomponent
@endsection
