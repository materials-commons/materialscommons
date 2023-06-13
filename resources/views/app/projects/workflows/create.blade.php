@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.create', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow
        @endslot

        @slot('body')
            @include('partials.workflows.create', [
                'storeRoute' => route('projects.workflows.store', [$project]),
                'cancelRoute' => route('projects.workflows.index', [$project]),
            ])
        @endslot
    @endcomponent
@stop
