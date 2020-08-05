@extends('layouts.app')

@section('pageTitle', 'View Project')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Project: {{$project->name}}
            <a class="float-right action-link"
               href="{{route('projects.edit', $project->id)}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            @if ($project->owner_id == auth()->id())
                <a data-toggle="modal" class="float-right action-link mr-4"
                   href="#project-delete-{{$project->id}}">
                    <i class="fas fa-trash-alt mr-2"></i>Delete
                </a>
                @component('app.projects.delete-project', ['project' => $project])
                @endcomponent
            @endif
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $project])
                <a class="ml-4 action-link" href="{{route('projects.users.index', [$project])}}">
                    {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
                </a>
                <a class="ml-4 action-link" href="{{route('projects.users.index', [$project])}}">
                    {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
                </a>
            @endcomponent

            <h5>There are {{$objectCounts->filesCount}} files totalling {{formatBytes($totalFilesSize)}}.</h5>
            <div class="row ml-1">
                <div class="col-4 bg-grey-9">
                    @include('app.projects._process-types')
                </div>
                <div class="col-3 bg-grey-9 ml-2">
                    @include('app.projects._object-types')
                </div>
                <div class="col-4 bg-grey-9 ml-2">
                    @include('app.projects._file-types')
                </div>
            </div>
        @endslot
    @endcomponent
@endsection
