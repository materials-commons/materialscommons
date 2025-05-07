@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.create', $project, $experiment))

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow in Study {{$experiment->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.create', [
                'storeRoute' => route('projects.experiments.workflows.store', [$project, $experiment]),
                'cancelRoute' => route('projects.experiments.show', [$project, $experiment]),
            ])
        @endslot
    @endcomponent
@stop
