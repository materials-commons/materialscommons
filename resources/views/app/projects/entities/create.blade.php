@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Create Sample</h3>
    <br/>

    @include('partials.entities.create', [
        'storeEntityRoute' => route('projects.entities.store', [$project]),
        'cancelRoute' => route('projects.entities.index', [$project]),
        'experiments' => $project->experiments
    ])
@stop
