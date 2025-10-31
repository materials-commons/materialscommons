@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3>Edit: {{$file->fullPath()}}</h3>
    <form method="post" action="{{route('projects.files.save-edited', [$project, $file])}}" id="file-edit">
        @csrf
        <div class="float-end">
            <a href="{{route('projects.files.show', [$project, $file])}}"
               class="action-link danger me-3">Cancel</a>
            <a class="action-link" href="#" onclick="document.getElementById('file-edit').submit()">Save</a>
        </div>
        <div class="mb-3">
            <textarea class="form-control" id="content" name="content" type="text"
                      placeholder="Content...">{{$fileContents($file)}}</textarea>
        </div>

    </form>
@stop
