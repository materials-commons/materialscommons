@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))

@section('content')
    @include('partials.entities._show', [
        'showRouteName' => 'projects.entities.show',
        'showRoute' => route('projects.entities.show', [$project, $entity]),
        'attributesRouteName' => 'projects.entities.attributes',
        'attributesRoute' => route('projects.entities.attributes', [$project, $entity]),
        'filesRouteName' => 'projects.entities.files',
        'filesRoute' => route('projects.entities.files', [$project, $entity]),
        'showActivityRoute' => $showActivityRoute ?? '',
        'showFileRoute' => $showFileRoute ?? ''
    ])
@endsection