@extends('layouts.project')

@section('pageTitle', 'Experiments')

@section('content')
    @component('components.card')
        @slot('header')
            Experiment {{$experiment->name}}
        @endslot

        @slot('body')
            @component('components.experiment-tabs', ['project' => $project, 'experiment' => $experiment])
            @endcomponent

            <div class="ml-2">
                Details here
            </div>
        @endslot
    @endcomponent
@stop
