@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @isset($project)
        @include('layouts.navs.app.project')
    @else
        @include('layouts.navs.public')
    @endisset
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File:
            <x-show-dir-path :project="$project" :file="$file->directory"/>{{$file->name}}


            @isset($project)
                {{--                <a class="float-end action-link" href="#">--}}
                {{--                    <i class="fas fa-edit mr-2"></i>Edit--}}
                {{--                </a>--}}

                {{--                <a class="float-end action-link mr-4" href="#">--}}
                {{--                    <i class="fas fa-trash-alt mr-2"></i>Delete--}}
                {{--                </a>--}}

                @if ($file->mime_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                    <a class="float-end action-link mr-4"
                       href="{{route('projects.files.create-experiment', [$project, $file])}}">
                        <i class="fas fa-file-import mr-2"></i>Create Study From Spreadsheet
                    </a>
                @endif
                <a class="action-link float-end mr-4"
                   href="{{route('projects.files.download', [$project, $file])}}">
                    <i class="fas fa-download mr-2"></i>Download File
                </a>

                <a class="action-link float-end mr-4" href="{{route('projects.files.delete', [$project, $file])}}">
                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                </a>
            @endisset

        @endslot

        @slot('body')
            @if(!$file->current)
                <h4>You are looking at a previous version of the file. You can see the current version
                    <a href="{{route('projects.files.show', [$project, $file->currentVersion()])}}">here.</a>
                    <a class="ml-4 btn btn-warning" href="{{route('projects.files.set-active', [$project, $file])}}">
                        <i class="fas fa-fw fa-history mr-2"></i>Set as active version
                    </a>
                </h4>

                <br>
            @endif
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
                'versionsRouteName' => 'projects.files.versions',
                'versionsRoute' => route('projects.files.versions', [$project, $file]),
            ])

            <br>

            @if(Request::routeIs('projects.files.show'))
                @include('app.files.tabs.file')
            @elseif (Request::routeIs('projects.files.entities'))
                @include('app.files.tabs.entities')
            @elseif (Request::routeIs('projects.files.activities'))
                @include('app.files.tabs.activities')
            @elseif (Request::routeIs('projects.files.attributes'))
                @include('app.files.tabs.attributes')
            @elseif (Request::routeIs('projects.files.experiments'))
                @include('app.files.tabs.experiments')
            @elseif (Request::routeIs('projects.files.versions'))
                @include('app.files.tabs.versions')
            @endif
        @endslot
    @endcomponent
@endsection
