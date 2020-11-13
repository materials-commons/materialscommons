@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Show Spreadsheet load for {{$etlRun->files[0]->name}} run on: {{$etlRun->created_at}}
            <a class="action-link float-right" style="cursor: pointer"
               hx-get="{{route('projects.experiments.etl_run.show-log', [$project, $experiment, $etlRun])}}"
               hx-target="#etl-log">
                <i class="fas fa-plus mr-2"></i>Refresh Log
            </a>
        @endslot

        @slot('body')
            <div hx-get="{{route('projects.experiments.etl_run.show-log', [$project, $experiment, $etlRun])}}"
                 hx-trigger="load" hx-target="#etl-log">
                <div id="etl-log"></div>
            </div>
        @endslot
    @endcomponent
@endsection