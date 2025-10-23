@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @isset($project)
        @include('layouts.navs.app.project')
    @else
        @include('layouts.navs.public')
    @endisset
@stop

@section('content')
    <x-show-dir-path :project="$project" :dir="$file->directory" :file="$file"/>

    @if(!$file->current)
        <h4>You are looking at a previous version of the file. You can see the current version
            <a href="{{route('projects.files.show', [$project, $file->currentVersion()])}}">here.</a>
            <a class="ms-4 btn btn-warning" href="{{route('projects.files.set-active', [$project, $file])}}">
                <i class="fas fa-fw fa-history me-2"></i>Set as active version
            </a>
        </h4>

        <br>
    @endif
    @include('app.files.tabs.tabs', [
        'showRouteName' => 'projects.files.show',
        'showRoute' => route('projects.files.show', [$project, $file]),
        'entitiesRouteName' => 'projects.files.entities',
        'entitiesRoute' => route('projects.files.entities', [$project, $file]),
        'activitiesRouteName' => 'projects.files.activities',
        'activitiesRoute' => route('projects.files.activities', [$project, $file]),
        'attributesRouteName' => 'projects.files.attributes',
        'attributesRoute' => route('projects.files.attributes', [$project, $file]),
        'experimentsRouteName' => 'projects.files.experiments',
        'experimentsRoute' => route('projects.files.experiments', [$project, $file]),
        'versionsRouteName' => 'projects.files.versions',
        'versionsRoute' => route('projects.files.versions', [$project, $file]),
    ])

    <br>

    @if(Request::routeIs('projects.files.show'))
        @include('app.files.tabs.file')
    @elseif (Request::routeIs('projects.files.entities'))
        @include('app.files.tabs.entities')
    @elseif (Request::routeIs('projects.files.activities'))
        @include('app.files.tabs.activities')
    @elseif (Request::routeIs('projects.files.attributes'))
        @include('app.files.tabs.attributes')
    @elseif (Request::routeIs('projects.files.experiments'))
        @include('app.files.tabs.experiments')
    @elseif (Request::routeIs('projects.files.versions'))
        @include('app.files.tabs.versions')
    @endif
@endsection
