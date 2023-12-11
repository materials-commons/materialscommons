@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.edit', $project, $experiment, $workflow))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Edit Workflow {{$workflow->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.edit', [
                'updateMethod' => 'put',
                'updateRoute' => route('projects.datasets.workflows.edit.update', [$project, $dataset, $workflow]),
                'cancelRoute' => route('projects.datasets.workflows.edit', [$project, $dataset])
            ])
        @endslot
    @endcomponent
@stop
