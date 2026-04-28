@extends('layouts.app')

@section('pageTitle', "{$project->name} - Delete File")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Delete File: {{$file->name}}</h3>
    <br/>

    <form method="post"
          action="{{route('projects.files.destroy', [$project, $file, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg])}}"
          id="delete-file">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" value="{{$file->name}}" name="name" readonly>
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description"
                      name="description" readonly>{{$file->description}}</textarea>
        </div>
        <div class="float-end">
            <a href="{{route('projects.folders.show', [$project, $dir, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg])}}"
               class="action-link me-3">
                Cancel
            </a>
            <a class="action-link danger" onclick="document.getElementById('delete-file').submit()" href="#">
                Delete
            </a>
        </div>
    </form>

    @include('common.errors')
@endsection
