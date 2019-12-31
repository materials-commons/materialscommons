@extends('layouts.app')

@section('pageTitle', 'Edit Workflow')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.edit', $project, $experiment, $workflow))

@section('content')
    @component('components.card')
        @slot('header')
            Edit Workflow {{$workflow->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.edit', [
                'updateRoute' => route('projects.experiments.workflows.update', [$project, $experiment, $workflow]),
                'cancelRoute' => route('projects.experiments.show', [$project, $experiment])
            ])
        @endslot
    @endcomponent
@stop
