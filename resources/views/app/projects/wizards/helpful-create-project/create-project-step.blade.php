@extends('layouts.app')

@section('pageTitle', 'Create Project')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3 class="text-center">Create Project</h3>
    <br/>

    @include('partials._create_project', [
        'createProjectRoute' => route('projects.store'),
        'cancelRoute' => route('projects.index')
    ])

    @include('common.errors')
@endsection
