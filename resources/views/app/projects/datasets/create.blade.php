@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text" value=""
                              placeholder="Description..."></textarea>
                </div>
                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('dataset-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@stop