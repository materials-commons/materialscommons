@extends('layouts.app')

@section('pageTitle', 'Create Project')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Project
        @endslot

        @slot('body')
            @include('app.projects._overview')
            @include('partials._create_project', [
                'createProjectRoute' => route('projects.store', ['show-overview' => request()->input('show-overview', false)]),
                'cancelRoute' => route('projects.index'),
                'createAndNext' => 'Create And Add Experiments',
                'createAndNextRoute' => route('projects.store', ['experiments-next' => true, 'show-overview' => request()->input('show-overview', false)])
            ])
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
