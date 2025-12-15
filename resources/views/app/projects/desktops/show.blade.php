@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    Showing desktop {{$desktopId}}
    <br/>
    <br/>
    <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}" class="btn btn-success">
        Submit Test Upload
    </a>
@stop
