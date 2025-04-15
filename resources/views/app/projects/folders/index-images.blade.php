@extends('layouts.app')

@section('pageTitle', "{$project->name} - Images")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            {{$folder->path}}
            <div class="float-right">
                <a href="{{route('projects.folders.show', [$project, $folder, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}"
                   class="action-link">
                    Done
                </a>
            </div>
        </x-slot>
        <x-slot name="body">
            <div class="container">
                @foreach($images as $image)
                    <div class="row">
                        <div class="col-lg-12 mt-2">
                            <hr/>
                            <div class="text-center">
                                <span>{{$image->name}}</span>
                                <br>
                                @foreach($image->entities as $entity)
                                    <span>Used in Sample:
                                        <a href="{{route('projects.entities.show-spread', [$project, $entity])}}">
                                            {{$entity->name}}
                                        </a>
                                    </span>
                                    <br>
                                @endforeach
                                <br>
                                <img src="{{route('projects.files.display', [$project, $image])}}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot>
    </x-card>
@stop
