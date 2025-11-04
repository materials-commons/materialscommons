@extends('layouts.app')

@section('pageTitle', "{$project->name} - Images")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">View Images in folder: {{$folder->path}}</h3>

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
@stop
