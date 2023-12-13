@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Process")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.activities.show', $project, $activity))--}}

@section('content')
    @include('partials.activities._show', [
        'showRouteName' => 'projects.activities.show',
        'showRoute' => route('projects.activities.show', [$project, $activity]),
        'entitiesRouteName' => 'projects.activities.entities',
        'entitiesRoute' => route('projects.activities.entities', [$project, $activity]),
        'filesRouteName' => 'projects.activities.files',
        'filesRoute' => route('projects.activities.files', [$project, $activity])
    ])
@endsection
