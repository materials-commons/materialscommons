@extends('layouts.app')

@section('pageTitle', 'Project Globus Downloads')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Globus Downloads
            <a class="action-link float-right" href="{{route('projects.globus.downloads.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i> New Globus Download
            </a>
        @endslot

        @slot('body')
            @include('partials.globus_downloads', ['showProject' => false])
        @endslot
    @endcomponent
@stop