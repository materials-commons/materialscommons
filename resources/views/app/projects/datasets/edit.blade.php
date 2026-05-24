@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.edit', $project, $dataset))

@section('content')
    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="text-muted mb-1" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.05em;">
                Editing Dataset
            </div>
            <h4 class="mb-0 fw-bold">{{ $dataset->name }}</h4>
        </div>
        <a href="{{ route('projects.datasets.show', [$project, $dataset]) }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-eye me-1"></i> View Dataset
        </a>
    </div>

    {{-- Unified status + navigation tabs --}}
    @include('app.projects.datasets._dataset-status', [
        'defaultRoute'   => route('projects.datasets.edit',           [$project, $dataset, 'public' => $isPublic]),
        'filesRoute'     => route('projects.datasets.files.edit',     [$project, $dataset, 'public' => $isPublic]),
        'workflowsRoute' => route('projects.datasets.workflows.edit', [$project, $dataset, 'public' => $isPublic]),
        'samplesRoute'   => route('projects.datasets.samples.edit',   [$project, $dataset, 'public' => $isPublic]),
    ])

    {{-- Issues / warnings (collapsible, shown below the tab bar) --}}
    <div class="border-start border-end border-bottom rounded-bottom px-3 py-2 mb-4 bg-light">
        @include('app.projects.datasets._dataset-issues')
    </div>

    {{-- Tab content --}}
    @if (Request::routeIs('projects.datasets.edit'))
        @include('app.projects.datasets.ce-tabs.details')
    @elseif (Request::routeIs('projects.datasets.files.edit'))
        @include('app.projects.datasets.ce-tabs.files', [
            'addFilesRouteName'       => 'projects.datasets.create-data.upload-files',
            'createDirectoryRouteName'=> 'projects.datasets.create-data.create-directory',
            'directoryPathRouteName'  => 'projects.datasets.files.edit',
        ])
    @elseif (Request::routeIs('projects.datasets.samples.edit'))
        @include('app.projects.datasets.ce-tabs.entities')
    @elseif (Request::routeIs('projects.datasets.activities.edit'))
        @include('app.projects.datasets.ce-tabs.activities')
    @elseif (Request::routeIs('projects.datasets.workflows.edit'))
        @include('app.projects.datasets.ce-tabs.workflows')
    @endif
@stop
