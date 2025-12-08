@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.edit', $project, $workflow))

@section('content')
    <h3 class="text-center">Edit Workflow {{$workflow->name}}</h3>
    <br/>

    @include('partials.workflows.edit', [
        'updateRoute' => route('projects.workflows.update', [$project, $workflow]),
        'cancelRoute' => route('projects.workflows.index', [$project])
    ])
@stop
