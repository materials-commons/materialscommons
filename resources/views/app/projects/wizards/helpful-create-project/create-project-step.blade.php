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
            @include('partials._create_project', [
                'createProjectRoute' => route('projects.store'),
                'cancelRoute' => route('projects.index')
            ])
        @endslot
    @endcomponent

    @include('common.errors')
@endsection