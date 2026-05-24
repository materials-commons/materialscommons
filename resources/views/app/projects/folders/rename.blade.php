@extends('layouts.app')

@section('pageTitle', "{$project->name} - Rename Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Rename Folder: {{$dir->name}}</h3>
    <br/>

    <form method="post"
          action="{{route('projects.folders.rename.update', [$project, $dir, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg])}}"
          id="rename-folder">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" value="{{$dir->name}}" name="name">
        </div>

        <div class="float-end">
            <a href="{{route('projects.folders.show', [$project, $dir, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg])}}"
               class="action-link me-3">
                Cancel
            </a>
            <a class="action-link" onclick="document.getElementById('rename-folder').submit()" href="#">
                Rename
            </a>
        </div>
    </form>

    @include('common.errors')
@endsection
