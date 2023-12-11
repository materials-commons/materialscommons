@extends('layouts.app')

@section('pageTitle', "{$project->name} - Edit Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.edit', $project, $workflow))

@section('content')
    @component('components.card')
        @slot('header')
            Edit Workflow {{$workflow->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.edit', [
                'updateRoute' => route('projects.workflows.update', [$project, $workflow]),
                'cancelRoute' => route('projects.workflows.index', [$project])
            ])
        @endslot
    @endcomponent
@stop
