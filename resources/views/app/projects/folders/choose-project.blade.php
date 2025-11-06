@extends('layouts.app')

@section('pageTitle', "{$project->name} - Select Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Select Project For Destination</h3>
    <br/>
    <a class="btn btn-success"
       href="{{route('projects.folders.show-for-copy', [$project, $file, $project->rootDir, $copyType])}}">
        Copy to Current Project ({{$project->name}})
    </a>
    <br>
    <br>
    <h4>Projects</h4>
    <br>
    <table id="projects" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Project</th>
            <th>Summary</th>
            <th>Owner</th>
            <th>Updated</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $proj)
            <tr>
                <td>
                    <a href="{{route('projects.folders.show-for-copy', [$proj, $file, $proj->rootDir, $copyType])}}"
                       class="">
                        {{$proj->name}}
                    </a>
                </td>
                <td>{{$proj->summary}}</td>
                <td>{{$proj->owner->name}}</td>
                <td>{{$proj->updated_at->diffForHumans()}}</td>
                <td>{{$proj->updated_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
