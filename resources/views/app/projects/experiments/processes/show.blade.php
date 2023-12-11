@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Process")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.activities.show', $project, $experiment, $activity))

@section('content')
    @include('partials.activities._show', [
        'showRouteName' => 'projects.experiments.activities.show',
        'showRoute' => route('projects.experiments.activities.show', [$project, $experiment, $activity]),
        'entitiesRouteName' => 'projects.experiments.activities.entities',
        'entitiesRoute' => route('projects.activities.entities', [$project, $experiment, $activity]),
        'filesRouteName' => 'projects.experiments.activities.files',
        'filesRoute' => route('projects.experiments.activities.files', [$project, $experiment, $activity])
    ])
@endsection
