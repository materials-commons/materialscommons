@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit Study: {{$experiment->name}}
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('projects.experiments.update', ['project' => $project, 'experiment' => $experiment])}}"
                  id="edit-experiment">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{old('name', $experiment->name)}}" name="name">
                </div>
                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" value="{{old('summary', $experiment->summary)}}"
                           name="summary">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description">{{old('description', $experiment->description)}}</textarea>
                </div>
                <input hidden name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project])}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('edit-experiment').submit()" href="#">
                        Save
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
