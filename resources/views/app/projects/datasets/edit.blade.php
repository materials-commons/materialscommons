@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.edit', $project, $dataset))

@section('content')
            Edit Dataset: {{$dataset->name}}

            @include('app.projects.datasets._dataset-status', [
                'defaultRoute' => route('projects.datasets.edit', [$project, $dataset, 'public' => $isPublic]),
                'filesRoute' => route('projects.datasets.files.edit', [$project, $dataset, 'public' => $isPublic]),
                'workflowsRoute' => route('projects.datasets.workflows.edit', [$project, $dataset, 'public' => $isPublic]),
                'samplesRoute' => route('projects.datasets.samples.edit', [$project, $dataset, 'public' => $isPublic]),
            ])
            <br>
            @include('app.projects.datasets.ce-tabs.tabs',[
                'defaultRoute' => route('projects.datasets.edit', [$project, $dataset, 'public' => $isPublic]),
                'defaultRouteName' => 'projects.datasets.edit',
                'filesRoute' => route('projects.datasets.files.edit', [$project, $dataset, 'public' => $isPublic]),
                'filesRouteName' => 'projects.datasets.files.edit',
                'workflowsRoute' => route('projects.datasets.workflows.edit', [$project, $dataset, 'public' => $isPublic]),
                'workflowsRouteName' => 'projects.datasets.workflows.edit',
                'samplesRoute' => route('projects.datasets.samples.edit', [$project, $dataset, 'public' => $isPublic]),
                'samplesRouteName' => 'projects.datasets.samples.edit',
                'processesRoute' => route('projects.datasets.activities.edit', [$project, $dataset, 'public' => $isPublic]),
                'processesRouteName' => 'projects.datasets.activities.edit',
            ])

            @if (Request::routeIs('projects.datasets.edit'))
                @include('app.projects.datasets.ce-tabs.details')
            @elseif (Request::routeIs('projects.datasets.files.edit'))
                @include('app.projects.datasets.ce-tabs.files', [
                    'addFilesRouteName' => 'projects.datasets.create-data.upload-files',
                    'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
                    'directoryPathRouteName' => 'projects.datasets.files.edit',
                ])
            @elseif (Request::routeIs('projects.datasets.samples.edit'))
                @include('app.projects.datasets.ce-tabs.entities')
            @elseif (Request::routeIs('projects.datasets.activities.edit'))
                @include('app.projects.datasets.ce-tabs.activities')
            @elseif (Request::routeIs('projects.datasets.workflows.edit'))
                @include('app.projects.datasets.ce-tabs.workflows')
            @endif
@stop
