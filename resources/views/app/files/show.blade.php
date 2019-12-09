@extends('layouts.app')

@section('pageTitle', 'View File')

@section('nav')
    @isset($project)
        @include('layouts.navs.app.project')
    @else
        @include('layouts.navs.public')
    @endisset
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}}

            @isset($project)
                <a class="float-right action-link" href="#">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>

                <a class="float-right action-link mr-4" href="#">
                    <i class="fas fa-trash-alt mr-2"></i>Delete
                </a>

                @if ($file->mime_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                    <a class="float-right action-link mr-4"
                       href="{{route('projects.files.create-experiment', [$project, $file])}}">
                        <i class="fas fa-file-import mr-2"></i>Create Experiment From Spreadsheet
                    </a>
                @endif
            @endisset

        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$file->name}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$file->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$file->updated_at->diffForHumans()}}</dd>
                    <dt class="col-sm-2">Mediatype</dt>
                    <dd class="col-sm-10">{{$file->mime_type}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$file->description}}</p>
            </div>

            <br>

            @isset($project)
                @include('app.files.tabs.project-tabs')
            @endisset

        @endslot
    @endcomponent
@endsection
