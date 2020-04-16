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
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $file])
                <span class="ml-4">Mediatype: {{$file->mime_type}}</span>
                @slot('bottom')
                    <a href="{{route('public.datasets.download_file', [$dataset, $file])}}">
                        Download file
                    </a>
                @endslot
            @endcomponent
            <hr>
            <br>

            @include('public.datasets.files.display-file')
        @endslot
    @endcomponent
@endsection
