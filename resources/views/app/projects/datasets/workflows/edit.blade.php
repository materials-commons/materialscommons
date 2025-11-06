@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.edit', $project, $experiment, $workflow))--}}

@section('content')
    <h3 class="text-center">Edit Workflow {{$workflow->name}}</h3>
    <br/>

    @include('partials.workflows.edit', [
        'updateMethod' => 'put',
        'updateRoute' => route('projects.datasets.workflows.edit.update', [$project, $dataset, $workflow]),
        'cancelRoute' => route('projects.datasets.workflows.edit', [$project, $dataset])
    ])
@stop
