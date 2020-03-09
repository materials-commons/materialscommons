@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

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
                <div class="form-group">
                    <label for="authors">Name</label>
                    <input class="form-control" value="{{$dataset->name}}" id="authors" type="text" readonly>
                </div>
                <div class="float-right">
                    <a href="#" class="action-link mr-3">
                        Edit Details
                    </a>
                    <a href="#" class="action-link">
                        Done And Review
                    </a>
                </div>
            </form>

            <br>
            <br>
            <div class="row justify-content-center">
                <div class="col-11">
                    <p>
                        Add or select the files, samples, processes and workflows that make up your dataset. When you
                        are done you can <a href="#">Review</a> your dataset. If you would like to edit the details,
                        such as tags, authors, etc... you can select the <a href="#">Edit Details</a> link here or
                        above.
                    </p>
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
                'processesRouteName' => 'projects.datasets.activities.create-data',
                'processesRoute' => route('projects.datasets.activities.create-data', [$project, $dataset])
            ])
            <br>

            @if (Request::routeIs('projects.datasets.create-data'))
                @include('app.projects.datasets.ce-tabs.files')
            @elseif (Request::routeIs('projects.datasets.samples.create-data'))
                @include('app.projects.datasets.ce-tabs.entities')
            @elseif (Request::routeIs('projects.datasets.activities.create-data'))
                @include('app.projects.datasets.ce-tabs.activities')
            @elseif (Request::routeIs('projects.datasets.workflows.create-data'))
                @include('app.projects.datasets.ce-tabs.workflows')
            @endif
        @endslot
    @endcomponent
@stop