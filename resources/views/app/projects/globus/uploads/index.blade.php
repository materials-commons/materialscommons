@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus Uploads")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Globus Uploads
            <a class="action-link float-right" href="{{route('projects.globus.uploads.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i> New Globus Upload
            </a>
        @endslot

        @slot('body')
            <x-card-container>
                @include('partials.globus_uploads', ['showProject' => false])
            </x-card-container>
        @endslot
    @endcomponent
@stop
