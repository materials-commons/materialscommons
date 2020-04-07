@extends('layouts.app')

@section('pageTitle', 'Edit Workflow')

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
                'updateMethod' => 'post',
                'updateRoute' => route('projects.datasets.workflows.edit.store', [$project, $dataset, $workflow]),
                'cancelRoute' => route('projects.datasets.workflows.edit', [$project, $dataset])
            ])
        @endslot
    @endcomponent
@stop