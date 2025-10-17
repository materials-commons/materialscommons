@extends('layouts.app')

@section('pageTitle', 'Delete Trigger')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>
            Delete Trigger: {{$trigger->name}}
            <a class="float-end action-link mr-4"
               href="{{route('projects.triggers.edit', [$project, $trigger])}}">
                <i class="fas fa-fw fa-edit mr-2"></i>Edit Trigger
            </a>
        </x-slot:header>
        <x-slot:body>
            <x-show-standard-details :item="$trigger"></x-show-standard-details>

            <div class="form-group">
                <label for="when">When</label>
                <input class="form-control" id="when" name="when" type="text" value="{{$trigger->when}}"
                       placeholder="When..." readonly>
            </div>

            <div class="form-group">
                <label for="what">What</label>
                <input class="form-control" id="what" name="what" type="text" value="{{$trigger->what}}"
                       placeholder="What..." readonly>
            </div>

            <br>
            <h4>Trigger Script:
                <a href="{{route('projects.files.show', [$project, $trigger->script->scriptFile])}}">
                    {{$trigger->script->scriptFile->fullPath()}}
                </a>
            </h4>
            <div class="row border ml-1">
                <x-display-file :file="$trigger->script->scriptFile"
                                :display-route="route('projects.files.display', [$project, $trigger->script->scriptFile])"/>
            </div>
            <br>
            <div class="float-end">
                <a href="{{route('projects.triggers.index', [$project])}}" class="btn btn-primary">Cancel</a>
                <a href="{{route('projects.triggers.destroy', [$project, $trigger])}}" class="btn btn-danger">Delete</a>
            </div>
        </x-slot:body>
    </x-card>
@stop