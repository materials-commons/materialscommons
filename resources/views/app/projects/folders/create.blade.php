@extends('layouts.app')

@section('pageTitle', 'Create Folder')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create folder in directory {{$directory->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.folders.store', [$project, $directory])}}" id="folder-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" placeholder="Folder...">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..."></textarea>
                </div>

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <input hidden id="directory_id" name="directory_id" value="{{$directory->id}}">

                <div class="float-right">
                    <a class="action-link danger mr-3"
                       href="{{route('projects.folders.show', [$project, $directory])}}">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('folder-create').submit()">
                        Create
                    </a>
                </div>
            </form>

            @include('common.errors')
        @endslot
    @endcomponent
@stop