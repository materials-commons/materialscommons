@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.entities.show', $project, $experiment, $entity))

@section('content')
    @include('partials.entities._show', [
        'showRouteName' => 'projects.experiments.entities.show',
        'showRoute' => route('projects.experiments.entities.show', [$project, $experiment, $entity]),
        'attributesRouteName' => 'projects.experiments.entities.attributes',
        'attributesRoute' => route('projects.experiments.entities.attributes', [$project, $experiment, $entity]),
        'filesRouteName' => 'projects.experiments.entities.files',
        'filesRoute' => route('projects.experiments.entities.files', [$project, $experiment, $entity]),
        'showActivityRoute' => $showActivityRoute ?? '',
        'showFileRoute' => $showFileRoute ?? ''
    ])
@endsection