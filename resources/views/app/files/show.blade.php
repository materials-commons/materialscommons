@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}}

            <a class="float-right action-link" href="#">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="float-right action-link mr-4" href="#">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>

            @if ($file->mime_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                <a class="float-right action-link mr-4"
                   href="{{route('projects.files.create-experiment', [$project, $file])}}">
                    <i class="fas fa-file-import mr-2"></i>Create Experiment From Spreadsheet
                </a>
            @endif

        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$file->name}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$file->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$file->updated_at->diffForHumans()}}</dd>
                    <dt class="col-sm-2">Mediatype</dt>
                    <dd class="col-sm-10">{{$file->mime_type}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$file->description}}</p>
            </div>

            <br>

            @include('app.files.tabs.tabs', [
                'showRouteName' => 'projects.files.show',
                'showRoute' => route('projects.files.show', [$project, $file]),
                'entitiesRouteName' => 'projects.files.entities',
                'entitiesRoute' => route('projects.files.entities', [$project, $file]),
                'activitiesRouteName' => 'projects.files.activities',
                'activitiesRoute' => route('projects.files.activities', [$project, $file]),
                'attributesRouteName' => 'projects.files.attributes',
                'attributesRoute' => route('projects.files.attributes', [$project, $file]),
                'experimentsRouteName' => 'projects.files.experiments',
                'experimentsRoute' => route('projects.files.experiments', [$project, $file]),
            ])

            <br>

            @if(Request::routeIs('projects.files.show'))
                @include('app.files.tabs.display-file')
            @elseif (Request::routeIs('projects.files.entities'))
                @include('app.files.tabs.entities', ['object' => $file])
            @elseif (Request::routeIs('projects.files.activities'))
                @include('app.files.tabs.activities', ['object' => $file])
            @elseif (Request::routeIs('projects.files.attributes'))
                @include('app.files.tabs.attributes')
            @elseif (Request::routeIs('projects.files.experiments'))
                @include('app.files.tabs.experiments', ['object' => $file])
            @endif

        @endslot
    @endcomponent
@endsection