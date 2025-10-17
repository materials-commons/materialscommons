@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset -
            @if (Request::routeIs('projects.datasets.create-data'))
                Add Files
            @elseif (Request::routeIs('projects.datasets.samples.create-data'))
                Add Samples
            @elseif (Request::routeIs('projects.datasets.activities.create-data'))
                Add Processes
            @elseif (Request::routeIs('projects.datasets.workflows.create-data'))
                Add Workflows
            @endif
        @endslot

        @slot('body')
            <form>
                <div class="mb-3">
                    <label for="authors">Name</label>
                    <input class="form-control" value="{{$dataset->name}}" id="authors" type="text" readonly>
                </div>
                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" value="{{$dataset->summary}}" id="summary" type="text" readonly>
                </div>
                <div class="float-end">
                    <a href="{{route('projects.datasets.edit', [$project, $dataset])}}" class="action-link me-3">
                        Edit Details
                    </a>
                    <a href="{{route('projects.datasets.show', [$project, $dataset])}}" class="action-link">
                        Done And Review
                    </a>
                </div>
            </form>

            <br>
            <br>
            <div class="row justify-content-center">
                <div class="col-11">
                    @include('app.projects.datasets._create-data-help')
                </div>
            </div>

            <br>
            <br>
            @include('app.projects.datasets.ce-tabs.tabs', [
                'defaultRouteName' => 'projects.datasets.create-data',
                'defaultRoute' => route('projects.datasets.create-data', [$project, $dataset]),
                'workflowsRouteName' => 'projects.datasets.workflows.create-data',
                'workflowsRoute' => route('projects.datasets.workflows.create-data', [$project, $dataset]),
                'samplesRouteName' => 'projects.datasets.samples.create-data',
                'samplesRoute' => route('projects.datasets.samples.create-data', [$project, $dataset]),
            ])
            <br>

            @if (Request::routeIs('projects.datasets.create-data'))
                @include('app.projects.datasets.ce-tabs.files',[
                    'addFilesRouteName' => 'projects.datasets.create-data.upload-files',
                    'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
                    'directoryPathRouteName' => 'projects.datasets.create-data',
                ])
            @elseif (Request::routeIs('projects.datasets.samples.create-data'))
                @include('app.projects.datasets.ce-tabs.entities')
                {{--            @elseif (Request::routeIs('projects.datasets.activities.create-data'))--}}
                {{--                @include('app.projects.datasets.ce-tabs.activities')--}}
            @elseif (Request::routeIs('projects.datasets.workflows.create-data'))
                @include('app.projects.datasets.ce-tabs.workflows')
            @endif
        @endslot
    @endcomponent
@stop
