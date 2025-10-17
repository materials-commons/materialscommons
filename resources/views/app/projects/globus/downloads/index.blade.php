@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus Downloads")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Globus Downloads
            <a class="action-link float-end" href="{{route('projects.globus.downloads.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i> New Globus Download
            </a>

            <a class="action-link float-end mr-4" href="{{route('projects.globus.downloads.index', [$project])}}">
                <i class="fas fa-sync mr-2"></i> Refresh
            </a>
        @endslot

        @slot('body')
            <x-card-container>
                @include('partials.globus_downloads', ['showProject' => false])
            </x-card-container>
        @endslot
    @endcomponent
@stop
