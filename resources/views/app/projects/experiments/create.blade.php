@extends('layouts.app')

@section('pageTitle', 'Create Project')

@section('content')
    @component('components.card')
        @slot('header')
            Create Experiment
        @endslot

        @slot('body')
            <form method="post" action="{{route('experiments.store', ['project' => $project->id])}}" id="experiment-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..."></textarea>
                </div>
                <div class="float-right">
                    <a href="{{route('experiments.index', ['project' => $project->id])}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('experiment-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection