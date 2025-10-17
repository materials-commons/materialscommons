@extends('layouts.app')

@section('pageTitle', "{$project->name} - Delete Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete File: {{$dir->name}}
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('projects.folders.destroy', [$project, $dir, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg])}}"
                  id="delete-folder">
                @csrf
                @method('delete')

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$dir->path}}" name="name" readonly>
                </div>
                {{--                <div class="mb-3">--}}
                {{--                    <label for="description">Description</label>--}}
                {{--                    <textarea class="form-control" id="description"--}}
                {{--                              name="description" readonly>{{$dir->description}}</textarea>--}}
                {{--                </div>--}}
                <div class="float-right">
                    <a href="{{route('projects.folders.show', [$project, $dir, 'destproj' => $destProj, 'destdir' => $destDir, 'arg' => $arg])}}"
                       class="action-link me-3">
                        Cancel
                    </a>
                    <a class="action-link danger" onclick="document.getElementById('delete-folder').submit()" href="#">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
