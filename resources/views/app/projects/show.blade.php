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
            <x-show-standard-details :item="$project">
                <a class="ml-3 fs-9" href="{{route('projects.users.index', [$project])}}">
                    {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
                </a>
                <a class="ml-3 fs-9" href="{{route('projects.users.index', [$project])}}">
                    {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
                </a>
                <span class="ml-3 fs-9 grey-5">Size: {{formatBytes($project->size)}}</span>
            </x-show-standard-details>
            <form>
                <div class="form-group">
                    <label for="counts">Counts</label>
                    <ul class="list-inline">
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Files ({{$project->file_count}})</span>
                        </li>
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Directories ({{$project->directory_count}})</span>
                        </li>
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Samples ({{$project->entities_count}})</span>
                        </li>
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Published Datasets ({{$project->published_datasets_count}})</span>
                        </li>
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Unpublished Datasets ({{$project->unpublished_datasets_count}})
                            </span>
                        </li>
                        <li class="list-inline-item mt-1">
                            <span class="fs-9 grey-5">Experiments ({{$project->experiments_count}})</span>
                        </li>
                    </ul>
                </div>
            </form>
            @include('partials.overview._overview')
        @endslot
    @endcomponent
@endsection