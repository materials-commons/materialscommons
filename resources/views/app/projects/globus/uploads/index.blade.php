@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus Uploads")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    <h3 class="text-center">Globus Uploads</h3>
    <a class="action-link float-end" href="{{route('projects.globus.uploads.create', [$project])}}">
        <i class="fas fa-plus me-2"></i> New Globus Upload
    </a>
    <br/>
    <br/>
    @include('partials.globus_uploads', ['showProject' => false])
@stop
