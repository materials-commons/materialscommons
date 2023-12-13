@extends('layouts.app')

@section('pageTitle', "{$project->name} - Samples")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            @if($category == "computational")
                Computations
            @else
                Samples
            @endif
            {{--            <a class="action-link float-right" href="{{route('projects.entities-export', [$project])}}">--}}
            {{--                <i class="fas fa-download mr-2"></i>Download As Excel--}}
            {{--            </a>--}}

            {{--            <a class="action-link float-right" href="{{route('projects.entities.create', [$project])}}">--}}
            {{--                <i class="fas fa-plus mr-2"></i>Create Sample--}}
            {{--            </a>--}}
        @endslot

        @slot('body')
            @include('partials.entities._entities-with-used-activities-table', ['showExperiment' => true])
        @endslot
    @endcomponent
@stop
