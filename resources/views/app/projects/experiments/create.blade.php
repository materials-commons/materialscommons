@extends('layouts.app')

@section('pageTitle', 'Create Experiment')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Experiment
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.experiments.store', ['project' => $project->id])}}"
                  id="experiment-create">
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

                <div class="form-group">
                    <p>
                        Materials Commons can <b>optionally</b> import a spreadsheet to create your experiment. This
                        will set up the
                        processes, samples and files. To see the format for the spreadsheet please read the
                        documentation
                        on <a href="{{makeHelpUrl("spreadsheets")}}" target="_blank">spreadsheets.</a>
                    </p>
                    @if ($excelFiles->count() !== 0)
                        <label for="file_id">Spreadsheet to import</label>
                        <select name="file_id" class="selectpicker col-lg-10" data-live-search="true"
                                title="Spreadsheet">
                            @foreach($excelFiles as $file)
                                @if ($file->directory->path === "/")
                                    <option data-tokens="{{$file->id}}" value="{{$file->id}}">/{{$file->name}}</option>
                                @else
                                    <option data-tokens="{{$file->id}}" value="{{$file->id}}">{{$file->directory->path}}
                                        /{{$file->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    @else
                        <span>There are no excel files in the project to build an experiment from.</span>
                    @endif
                </div>

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project->id])}}" class="action-link danger mr-3">
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
