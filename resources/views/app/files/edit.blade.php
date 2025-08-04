@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>Edit: {{$file->fullPath()}}</x-slot:header>
        <x-slot:body>
            <x-card-container>
                <form method="post" action="{{route('projects.files.save-edited', [$project, $file])}}" id="file-edit">
                    @csrf
                    <div class="form-group">
                        <label for="content">File</label>
                        <textarea class="form-control" id="content" name="content" type="text"
                                  placeholder="Content...">{{$fileContents($file)}}</textarea>
                    </div>
                    <div class="float-right">
                        <a href="{{route('projects.files.show', [$project, $file])}}"
                           class="action-link danger mr-3">Cancel</a>
                        <a class="action-link" href="#" onclick="document.getElementById('file-edit').submit()">Save</a>
                    </div>
                </form>
            </x-card-container>
        </x-slot:body>
    </x-card>
@stop
