@extends('layouts.app')

@section('pageTitle', 'Create Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Sample
        @endslot

        @slot('body')
            @include('partials.entities.create', [
                'storeEntityRoute' => route('projects.entities.store', [$project]),
                'cancelRoute' => route('projects.entities.index', [$project]),
                'experiments' => $project->experiments
            ])
        @endslot
    @endcomponent
@stop