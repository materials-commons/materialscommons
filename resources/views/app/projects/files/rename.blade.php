@extends('layouts.app')

@section('pageTitle', "{$project->name} - Rename File")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Rename File: {{$file->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.files.rename.update', [$project, $file])}}"
                  id="rename-file">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$file->name}}" name="name">
                </div>

                @if($file->mime_type == "url")
                    <label for="url">URL</label>
                    <input class="form-control" id="url" value="{{$file->url}}" name="url">
                @endif

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">

                <div class="float-right">
                    @if($file->mime_type == "url")
                        <a href="{{route('projects.folders.show', [$project, $file->directory_id])}}" class="action-link mr-3">
                            Cancel
                        </a>
                    @else
                        <a href="{{route('projects.files.show', [$project, $file])}}" class="action-link mr-3">
                            Cancel
                        </a>
                    @endif
                    <a class="action-link" onclick="document.getElementById('rename-file').submit()" href="#">
                        Rename
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
