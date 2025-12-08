@extends('layouts.app')

@section('pageTitle', 'Create Project')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3>Create Project</h3>
    @include('app.projects._overview')
    @include('partials._create_project', [
        'createProjectRoute' => route('projects.store', ['show-overview' => request()->query('show-overview', false)]),
        'cancelRoute' => route('projects.index'),
        'createAndNext' => 'Create And Add Studies',
        'createAndNextRoute' => route('projects.store', ['experiments-next' => true, 'show-overview' => request()->query('show-overview', false)])
    ])

    @include('common.errors')
@endsection
