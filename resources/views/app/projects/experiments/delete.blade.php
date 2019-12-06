@extends('layouts.app')

@section('pageTitle', 'Delete Experiment')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete Experiment: {{$experiment->name}}
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('projects.experiments.destroy', ['project' => $project, 'experiment' => $experiment])}}"
                  id="delete-experiment">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$experiment->name}}" name="name" readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description" readonly>{{$experiment->description}}</textarea>
                </div>
                <input hidden name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project])}}" class="action-link danger mr-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('delete-experiment').submit()" href="#">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection