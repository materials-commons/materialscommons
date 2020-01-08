@extends('layouts.app')

@section('pageTitle', 'Show Workflow')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.show', $project, $workflow))

@section('content')
    @component('components.card')
        @slot('header')
            Show Workflow {{$workflow->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.show')
        @endslot
    @endcomponent
@stop