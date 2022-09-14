@extends('layouts.app')

@section('pageTitle', 'Rename File')

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

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">

                <div class="float-right">
                    <a href="{{route('projects.files.show', [$project, $file])}}" class="action-link mr-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('rename-file').submit()" href="#">
                        Rename
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
