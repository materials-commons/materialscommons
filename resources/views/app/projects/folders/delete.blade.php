@extends('layouts.app')

@section('pageTitle', 'Delete Folder')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete File: {{$dir->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.folders.destroy', [$project, $dir])}}" id="delete-folder">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$dir->name}}" name="name" readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description" readonly>{{$dir->description}}</textarea>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.folders.show', [$project, $dir])}}" class="action-link mr-3">
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
