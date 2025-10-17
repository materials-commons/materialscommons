@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))

@section('content')
    @component('app.projects.delete-project', ['project' => $project])
    @endcomponent
    {{--    <x-projects.tabs :experiments="$project->experiments"/>--}}
    {{--    <br>--}}
    <x-card>
        <x-slot:header>
            <span id="project-intro">Project: {{$project->name}}</span>
            <a class="float-end action-link"
               href="{{route('projects.edit', $project->id)}}">
                <i class="fas fa-edit me-2"></i>Edit
            </a>

            @if ($project->owner_id == auth()->id())
                <a data-toggle="modal" class="float-end action-link me-4"
                   href="#project-delete-{{$project->id}}">
                    <i class="fas fa-trash-alt me-2"></i>Delete
                </a>

            @endif

            <span id="mark-project">
            @if(auth()->user()->isActiveProject($project))
                <a hx-get="{{route('projects.unmark-as-active', [$project, 'target' => 'mark-project'])}}"
                   hx-target="#mark-project"
                   class="action-link float-end me-4 cursor-pointer">
                    <i class="fas fa-star text-warning-2 me-1"></i> Active Project
                </a>
            @else
                <a hx-get="{{route('projects.mark-as-active', [$project, 'target' => 'mark-project'])}}"
                   hx-target="#mark-project"
                   class="action-link float-end me-4 cursor-pointer">
                    <i class="fas fa-star me-1"></i> Active Project
                </a>
            @endif
            </span>
        </x-slot:header>

        <x-slot:body>
            @include('app.projects.tabs.tabs')
            <div class="mt-2">
                @if(Request::routeIs('projects.show'))
                    @include('app.projects.tabs.home')
                @elseif(Request::routeIs('projects.overview'))
                    @include('app.projects.tabs.overview')
                @elseif (Request::routeIs('projects.data-dictionary.entities'))
                    @include('app.projects.tabs.entity-attributes')
                @elseif(Request::routeIs('projects.data-dictionary.activities'))
                    @include('app.projects.tabs.activity-attributes')
                @endif
            </div>
        </x-slot:body>
    </x-card>
@endsection
