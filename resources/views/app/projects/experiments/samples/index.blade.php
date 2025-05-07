@extends('layouts.app')

@section('pageTitle', "{$project->name} - Studies")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Study {{$experiment->name}}
        @endslot

        @slot('body')
            @component('components.experiment-tabs', ['project' => $project, 'experiment' => $experiment])
            @endcomponent

            <div class="ml-2">
                Samples here {{$experiment->entities()->count()}}
            </div>
        @endslot
    @endcomponent
@stop
