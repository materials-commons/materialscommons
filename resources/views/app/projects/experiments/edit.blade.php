@extends('layouts.app')

@section('pageTitle', 'Edit Experiment')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit Experiment: {{$experiment->name}}
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('projects.experiments.update', ['project' => $project, 'experiment' => $experiment])}}"
                  id="edit-experiment">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$experiment->name}}" name="name">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description">{{$experiment->description}}</textarea>
                </div>
                <input hidden name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project])}}" class="action-link danger mr-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('edit-experiment').submit()" href="#">
                        Submit Changes
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection