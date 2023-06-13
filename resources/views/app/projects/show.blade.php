@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

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
            @include('app.projects.tabs.tabs')
            <div class="mt-2">
                @if(Request::routeIs('projects.show'))
                    @include('app.projects.tabs.overview')
                @elseif (Request::routeIs('projects.data-dictionary.entities'))
                    @include('app.projects.tabs.entity-attributes')
                @elseif(Request::routeIs('projects.data-dictionary.activities'))
                    @include('app.projects.tabs.activity-attributes')
                @endif
            </div>
        @endslot
    @endcomponent
@endsection
