@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.create', $project, $experiment))

@section('content')
    <h3 class="text-center">Create Workflow in Study {{$experiment->name}}</h3>
    <br/>

    @include('partials.workflows.create', [
        'storeRoute' => route('projects.experiments.workflows.store', [$project, $experiment]),
        'cancelRoute' => route('projects.experiments.show', [$project, $experiment]),
    ])
@stop
