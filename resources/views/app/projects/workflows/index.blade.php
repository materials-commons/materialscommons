@extends('layouts.app')

@section('pageTitle', 'Project Workflows')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Workflows for project: {{$project->name}}
        @endslot

        @slot('body')
            @include('partials.workflows.index', [
                'workflows' => $workflows
            ])
        @endslot
    @endcomponent
@stop