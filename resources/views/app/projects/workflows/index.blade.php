@extends('layouts.app')

@section('pageTitle', "{$project->name} - Workflows")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.index', $project))

@section('content')
    <h3 class="text-center">Workflows for project: {{$project->name}}</h3>
    <br/>

    <div class="float-end me-2">
        <a href="{{route('projects.workflows.create', [$project])}}" class="action-link">
            <i class="fas fa-fw fa-plus"></i> New Workflow
        </a>
    </div>
    @include('partials.workflows.index', [
        'workflows' => $workflows,
        'editProjectWorkflowRoute' => 'projects.workflows.edit',
    ])
@stop
