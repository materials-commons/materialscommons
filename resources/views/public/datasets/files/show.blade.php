@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.files.show', $dataset, $file))

@section('content')

    <h3 class="text-center">File: {{$file->name}}</h3>

    <br/>
    <x-card-container>
        <x-show-standard-details :item="$file">
            <span class="ms-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
            <span class="ms-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
            @auth
                <a class="action-link float-end"
                   href="{{route('public.datasets.download_file', [$dataset, $file])}}">
                    <i class="fas fa-download me-2"></i>Download File
                </a>
            @endauth
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
@endsection
