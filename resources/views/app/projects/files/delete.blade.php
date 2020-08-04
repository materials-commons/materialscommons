@extends('layouts.app')

@section('pageTitle', 'Delete File')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete File: {{$file->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.files.destroy', [$project, $file])}}" id="delete-file">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$file->name}}" name="name" readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description" readonly>{{$file->description}}</textarea>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.files.show', [$project, $file])}}" class="action-link mr-3">
                        Cancel
                    </a>
                    <a class="action-link danger" onclick="document.getElementById('delete-file').submit()" href="#">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
