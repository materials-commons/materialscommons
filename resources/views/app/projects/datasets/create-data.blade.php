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
            @include('app.projects.datasets.edit-tabs.tabs')
            <br>

            @if (Request::routeIs('projects.datasets.edit'))
                @include('app.projects.datasets.edit-tabs.files')
            @elseif (Request::routeIs('projects.datasets.samples.edit'))
                @include('app.projects.datasets.edit-tabs.entities')
            @elseif (Request::routeIs('projects.datasets.activities.edit'))
                @include('app.projects.datasets.edit-tabs.activities')
            @elseif (Request::routeIs('projects.datasets.workflows.edit'))
                @include('app.projects.datasets.edit-tabs.workflows')
            @endif
        @endslot
    @endcomponent
@stop