@extends('layouts.app')

@section('pageTitle', 'Create Workflow')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.workflows.create', $project))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow
        @endslot

        @slot('body')
            @include('partials.workflows.create', [
                'storeRoute' => route('projects.datasets.workflows.edit.store', [$project, $dataset]),
                'cancelRoute' => route('projects.datasets.workflows.edit', [$project, $dataset]),
            ])
        @endslot
    @endcomponent
@stop
