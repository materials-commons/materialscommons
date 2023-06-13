@extends('layouts.app')

@section('pageTitle', "{$project->name} - Rename Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Rename Folder: {{$dir->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.folders.rename.update', [$project, $dir])}}"
                  id="rename-folder">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$dir->name}}" name="name">
                </div>

                <div class="float-right">
                    <a href="{{route('projects.folders.show', [$project, $dir])}}" class="action-link mr-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('rename-folder').submit()" href="#">
                        Rename
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
