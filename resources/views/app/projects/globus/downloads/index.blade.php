@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus Downloads")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')

    <h3 class="text-center">Globus Downloads</h3>
    <div>
        <a class="action-link float-end" href="{{route('projects.globus.downloads.create', [$project])}}">
            <i class="fas fa-plus me-2"></i> New Globus Download
        </a>

        <a class="action-link float-end me-4" href="{{route('projects.globus.downloads.index', [$project])}}">
            <i class="fas fa-sync me-2"></i> Refresh
        </a>
    </div>
    <br/>
    <br/>
    @include('partials.globus_downloads', ['showProject' => false])
@stop
