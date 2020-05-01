@extends('layouts.app')

@section('pageTitle', 'Edit Project')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit Project: {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.update', $project->id)}}" id="edit-project">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{old('name', $project->name)}}"
                           name="name">
                </div>
                <div class="form-group">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" value="{{old('summary', $project->summary)}}"
                           name="summary">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description">{{old('description', $project->description)}}</textarea>
                </div>
                <div class="form-group form-check-inline">
                    <input type="hidden" name="default_project" value="0">
                    <input type="checkbox" class="form-check-input" id="default_project"
                           value="1" name="default_project"
                            {{old('default_project', $project->default_project) ? 'checked' : ''}}>
                    <label class="form-check-label" for="default_project">Default Project?</label>
                </div>
                <div class="form-group form-check-inline">
                    <input type="hidden" name="is_active" value="0"/>
                    <input type="checkbox" class="form-check-input" id="is_active"
                           value="1" name="is_active" {{$project->is_active ? 'checked' : ''}}>
                    <label class="form-check-label" for="is_active">Is Active?</label>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.index')}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" onclick="document.getElementById('edit-project').submit()" href="#">
                        Save
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
