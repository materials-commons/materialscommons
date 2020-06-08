@extends('layouts.app')

@section('pageTitle', 'Reload Experiment')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Reload Experiment
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('projects.experiments.reload', [$project, $experiment])}}"
                  id="experiment-reload">
                @csrf
                @method('put')
                <label for="file_id">Reload Experiment From</label>
                <select name="file_id" class="selectpicker col-lg-10" data-live-search="true"
                        title="Select Spreadsheet">
                    @foreach($excelFiles as $file)
                        @if ($file->directory->path === "/")
                            <option data-tokens="{{$file->id}}" value="{{$file->id}}">/{{$file->name}}</option>
                        @else
                            <option data-tokens="{{$file->id}}" value="{{$file->id}}">{{$file->directory->path}}
                                /{{$file->name}}</option>
                        @endif
                    @endforeach
                </select>
                <div class="float-right">
                    <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link mr-3" href="#"
                       onclick="document.getElementById('experiment-reload').submit()">
                        Reload
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@stop