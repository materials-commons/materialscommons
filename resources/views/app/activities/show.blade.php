@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Sample: {{$activity->name}}
            <a class="float-right action-link" href="#">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="float-right action-link mr-4" href="#">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$activity->name}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$activity->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$activity->updated_at->diffForHumans()}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$activity->description}}</p>
            </div>

            <br>
            @if (Request::routeIs('projects.experiments.activities*'))
                @include('app.activities.tabs.tabs', [
                    'showRouteName' => 'projects.experiments.activities.show',
                    'showRoute' => route('projects.experiments.activities.show', [$project, $experiment, $activity]),
                    'filesRouteName' => 'projects.experiments.activities.files',
                    'filesRoute' => route('projects.experiments.activities.files', [$project, $experiment, $activity])
                ])
            @elseif (Request::routeIs('projects.activities*'))
                @include('app.activities.tabs.tabs', [
                    'showRouteName' => 'projects.activities.show',
                    'showRoute' => route('projects.activities.show', [$project, $activity]),
                    'filesRouteName' => 'projects.activities.files',
                    'filesRoute' => route('projects.activities.files', [$project, $activity])
                ])
            @endif
            <br>
            @if (Request::routeIs('projects.experiments.activities*'))
                @if (Request::routeIs('projects.experiments.activities.show*'))
                    @include('app.activities.tabs.entities-tab')
                @elseif (Request::routeIs('projects.experiments.activities.files*'))
                    @include('app.activities.tabs.files-tab')
                @endif
            @elseif (Request::routeIs('projects.activities*'))
                @if (Request::routeIs('projects.activities.show*'))
                    @include('app.activities.tabs.entities-tab')
                @elseif (Request::routeIs('projects.activities.files*'))
                    @include('app.activities.tabs.files-tab')
                @endif
            @endif

        @endslot
    @endcomponent
@endsection