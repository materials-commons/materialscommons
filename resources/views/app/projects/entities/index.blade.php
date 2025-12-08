@extends('layouts.app')

@section('pageTitle', "{$project->name} - Samples")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*'))
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))
@else
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.index', $project))
@endif

@section('content')
            {{--            <a class="action-link float-end" href="{{route('projects.entities-export', [$project])}}">--}}
            {{--                <i class="fas fa-download me-2"></i>Download As Excel--}}
            {{--            </a>--}}

            {{--            <a class="action-link float-end" href="{{route('projects.entities.create', [$project])}}">--}}
            {{--                <i class="fas fa-plus me-2"></i>Create Sample--}}
            {{--            </a>--}}
            @include('partials.entities._entities-with-used-activities-table', ['showExperiment' => $showExperiment])
@stop
