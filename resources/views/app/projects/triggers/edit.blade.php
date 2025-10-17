@extends('layouts.app')

@section('pageTitle', 'Edit Trigger')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>Edit Trigger: {{$trigger->name}}</x-slot:header>
        <x-slot:body>
            <form method="post" action="{{route('projects.triggers.update', [$project, $trigger])}}" id="trigger-edit">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$trigger->name}}"
                           placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description...">{{$trigger->description}}</textarea>
                </div>

                <div class="form-group">
                    <label for="what">What</label>
                    <input class="form-control" id="what" name="what" type="text" value="{{$trigger->what}}"
                           placeholder="What...">
                </div>

                <div class="form-group">
                    <label for="name">When</label>
                    <input class="form-control" id="when" name="when" type="text" value="{{$trigger->when}}"
                           placeholder="When...">
                </div>

                <div class="form-group">
                    <label for="script_file_id">Select Script</label>
                    <select name="script_file_id" class="selectpicker col-lg-10" data-live-search="true"
                            data-style="btn-light no-tt"
                            title="Select Script">
                        <option value=""></option>
                        @foreach($scripts as $script)
                            <option data-tokens="{{$script->scriptFile->id}}" value="{{$script->scriptFile->id}}"
                                    style="text-transform: none"
                                    @if($trigger->script->id == $script->id) selected @endif>
                                {{$script->scriptFile->fullPath()}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="float-end">
                    <a href="{{route('projects.triggers.index', [$project])}}" class="action-link danger me-3">
                        Cancel
                    </a>

                    <a class="action-link"
                       href="#" onclick="document.getElementById('trigger-edit').submit()">
                        Save Changes
                    </a>
                </div>
            </form>
        </x-slot:body>
    </x-card>
@stop
