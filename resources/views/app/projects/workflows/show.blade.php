@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Workflow")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.workflows.show', $project, $workflow))

@section('content')
    <h3 class="text-center">Show Workflow {{$workflow->name}}</h3>
    <br/>

    @include('partials.workflows.show')
@stop
