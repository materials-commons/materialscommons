@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if (Request::routeIs('projects.experiments*'))
    @section('breadcrumbs', Breadcrumbs::render('projects.experiments.activities.show', $project, $experiment, $activity))
@else
    @section('breadcrumbs', Breadcrumbs::render('projects.activities.show', $project, $activity))
@endif

@section('content')
    @component('components.card')
        @slot('header')
            Process: {{$activity->name}}
            <a class="float-right action-link" href="#">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="float-right action-link mr-4" href="#">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
        @endslot

        @slot('body')
            @component('components.items-details', ['item' => $activity])
            @endcomponent
            <hr>
            <br>
            @if (Request::routeIs('projects.experiments.activities*'))
                @include('app.activities.tabs.tabs', [
                    'showRouteName' => 'projects.experiments.activities.show',
                    'showRoute' => route('projects.experiments.activities.show', [$project, $experiment, $activity]),
                    'entitiesRouteName' => 'projects.experiments.activities.entities',
                    'entitiesRoute' => route('projects.experiments.activities.entities', [$project, $experiment, $activity]),
                    'filesRouteName' => 'projects.experiments.activities.files',
                    'filesRoute' => route('projects.experiments.activities.files', [$project, $experiment, $activity])
                ])
            @elseif (Request::routeIs('projects.activities*'))
                @include('app.activities.tabs.tabs', [
                    'showRouteName' => 'projects.activities.show',
                    'showRoute' => route('projects.activities.show', [$project, $activity]),
                    'entitiesRouteName' => 'projects.activities.entities',
                    'entitiesRoute' => route('projects.activities.entities', [$project, $activity]),
                    'filesRouteName' => 'projects.activities.files',
                    'filesRoute' => route('projects.activities.files', [$project, $activity])
                ])
            @endif
            <br>
            @if (Request::routeIs('projects.experiments.activities*'))
                @if (Request::routeIs('projects.experiments.activities.show*'))
                    @include('app.activities.tabs.attributes-tab')
                @elseif (Request::routeIs('projects.experiments.activities.entities'))
                    @include('app.activities.tabs.entities-tab')
                @elseif (Request::routeIs('projects.experiments.activities.files*'))
                    @include('app.activities.tabs.files-tab')
                @endif
            @elseif (Request::routeIs('projects.activities*'))
                @if (Request::routeIs('projects.activities.show*'))
                    @include('app.activities.tabs.attributes-tab')
                @elseif (Request::routeIs('projects.activities.entities'))
                    @include('app.activities.tabs.entities-tab')
                @elseif (Request::routeIs('projects.activities.files*'))
                    @include('app.activities.tabs.files-tab')
                @endif
            @endif

        @endslot
    @endcomponent
@endsection
