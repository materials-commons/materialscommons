@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset
        @endslot

        @slot('body')
            <div class="form-group">
                <label for="authors">Name</label>
                <input class="form-control" value="{{$dataset->name}}" id="authors" type="text" readonly>
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