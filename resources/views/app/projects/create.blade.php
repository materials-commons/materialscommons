@extends('layouts.app')

@section('pageTitle', 'Create Project')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Create Project
        </x-slot>

        <x-slot name="body">
            @include('app.projects._overview')
            @include('partials._create_project', [
                'createProjectRoute' => route('projects.store', ['show-overview' => request()->query('show-overview', false)]),
                'cancelRoute' => route('projects.index'),
                'createAndNext' => 'Create And Add Studies',
                'createAndNextRoute' => route('projects.store', ['experiments-next' => true, 'show-overview' => request()->query('show-overview', false)])
            ])
        </x-slot>
    </x-card>

    @include('common.errors')
@endsection
