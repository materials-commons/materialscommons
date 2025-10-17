@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Project {{$project->name}} Files
        @endslot

        @slot('body')
            <form action="{{route('projects.files.store', ['project' => $project->id])}}" method="POST"
                  enctype="multipart/form-data" id="file-upload">
                @csrf
                <div class="mb-3">
                    <label for="file-upload-input">Upload File</label>
                    <input type="file" class="form-control" id="file-upload-input" name="file">
                </div>
                <div class="float-right">
                    <a href="{{route('projects.files.index', ['project' => $project])}}"
                       class="action-link danger me-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('file-upload').submit()">
                        Upload
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@stop

