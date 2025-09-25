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
            @auth
                <a class="action-link float-right"
                   href="{{route('public.datasets.download_file', [$dataset, $file])}}">
                    <i class="fas fa-download mr-2"></i>Download File
                </a>
            @endauth
        @endslot

        @slot('body')
            <x-card-container>
                <x-show-standard-details :item="$file">
                    <span class="ml-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
                    <span class="ml-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
                </x-show-standard-details>

                <hr>
                <br>
                @auth
                    @include('partials.files._display-file', [
                        'displayRoute' => route('public.datasets.files.display', [$dataset, $file])
                    ])
                @else
                    <br>
                    <h5 class="mt-3">
                        To view this file please
                        <a href="{{route('login')}}">Login</a> or <a href="{{route('register')}}">Register</a>.
                    </h5>
                    <br>
                @endauth
            </x-card-container>
        @endslot
    @endcomponent
@endsection
