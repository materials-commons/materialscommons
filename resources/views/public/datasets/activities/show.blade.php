@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.activities.show', $dataset, $activity))

@section('content')
    @include('partials.activities._show', [
        'showRouteName' => 'public.datasets.activities.show',
        'showRoute' => route('public.datasets.activities.show', [$dataset, $activity]),
        'entitiesRouteName' => 'public.datasets.entities.show',
        'entitiesRoute' => route('public.datasets.entities.show', [$project, $experiment, $activity]),
        'filesRouteName' => 'projects.experiments.activities.files',
        'filesRoute' => route('projects.experiments.activities.files', [$project, $experiment, $activity])
    ])
@endsection