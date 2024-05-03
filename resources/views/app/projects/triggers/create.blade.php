@extends('layouts.app')

@section('pageTitle', 'Create Trigger')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>Create Trigger</x-slot:header>
        <x-slot:body>
            <form method="post" action="{{route('projects.triggers.store', [$project])}}" id="trigger-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                           placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description...">{{old('description')}}</textarea>
                </div>

                <div class="form-group">
                    <label for="what">What</label>
                    <input class="form-control" id="what" name="what" type="text" value="{{old('what')}}"
                           placeholder="What...">
                </div>

                <div class="form-group">
                    <label for="name">When</label>
                    <input class="form-control" id="when" name="when" type="text" value="{{old('when')}}"
                           placeholder="When...">
                </div>

                <div class="form-group">
                    <label for="name">Select Script</label>
                    <select name="script_file_id" class="selectpicker col-lg-10" data-live-search="true"
                            title="Select Script">
                        <option value=""></option>
                        @foreach($scripts as $script)
                            <option data-tokens="{{$script->scriptFile->id}}" value="{{$script->scriptFile->id}}">
                                {{$script->scriptFile->fullPath()}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.triggers.index', [$project])}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link"
                       href="#" onclick="document.getElementById('trigger-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        </x-slot:body>
    </x-card>
@stop