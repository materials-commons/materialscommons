@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study Processes")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            {{$experiment->name}}
        @endslot

        @slot('body')
            @component('components.experiment-tabs', ['project' => $project, 'experiment' => $experiment])
            @endcomponent

            <div class="ml-2">
                Processes here {{$experiment->activities()->count()}}
            </div>
        @endslot
    @endcomponent
@stop
