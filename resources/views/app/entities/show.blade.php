@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if (Request::routeIs('projects.experiments*'))
    @section('breadcrumbs', Breadcrumbs::render('projects.experiments.entities.show', $project, $experiment, $entity))
@else
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))
@endif

@section('content')
    @component('components.card')
        @slot('header')
            Sample: {{$entity->name}}
            <a class="float-right action-link" href="#">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="float-right action-link mr-4" href="#">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
        @endslot

        @slot('body')
            @component('components.items-details', ['item' => $entity])
            @endcomponent

            <hr>

            <br>
            @if (Request::routeIs('projects.experiments.entities*'))
                @include('app.entities.tabs.tabs', [
                    'showRouteName' => 'projects.experiments.entities.show',
                    'showRoute' => route('projects.experiments.entities.show', [$project, $experiment, $entity]),
                    'filesRouteName' => 'projects.experiments.entities.files',
                    'filesRoute' => route('projects.experiments.entities.files', [$project, $experiment, $entity])
                ])
            @elseif (Request::routeIs('projects.entities*'))
                @include('app.entities.tabs.tabs', [
                    'showRouteName' => 'projects.entities.show',
                    'showRoute' => route('projects.entities.show', [$project, $entity]),
                    'filesRouteName' => 'projects.entities.files',
                    'filesRoute' => route('projects.entities.files', [$project, $entity])
                ])
            @endif
            <br>
            @if (Request::routeIs('projects.experiments.entities*'))
                @if (Request::routeIs('projects.experiments.entities.show*'))
                    @include('app.entities.tabs.activities-tab')
                @elseif (Request::routeIs('projects.experiments.entities.files*'))
                    @include('app.entities.tabs.files-tab')
                @endif
            @elseif (Request::routeIs('projects.entities*'))
                @if (Request::routeIs('projects.entities.show*'))
                    @include('app.entities.tabs.activities-tab')
                @elseif (Request::routeIs('projects.entities.files*'))
                    @include('app.entities.tabs.files-tab')
                @endif
            @endif

        @endslot
    @endcomponent
@endsection
