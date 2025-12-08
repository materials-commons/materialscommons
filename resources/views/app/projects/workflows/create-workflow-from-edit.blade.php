@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.workflows.create', $project))--}}

@section('content')
    <h3 class="text-center">Create Workflow</h3>
    <br/>

    @include('partials.workflows.create', [
        'storeRoute' => route('projects.datasets.workflows.edit.store', [$project, $dataset]),
        'cancelRoute' => route('projects.datasets.workflows.edit', [$project, $dataset]),
    ])
@stop
