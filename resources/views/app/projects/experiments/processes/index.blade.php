@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Experiment {{$experiment->name}}
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
