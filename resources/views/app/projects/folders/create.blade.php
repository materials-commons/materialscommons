@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create folder in directory {{$directory->name}}
        @endslot

        @slot('body')
            @include('app.projects.folders._create', [
                'storeDirectoryRoute' => route('projects.folders.store', [$project, $directory, 'arg' => $arg]),
                'cancelRoute' => route('projects.folders.show', [$project, $directory, 'arg' => $arg])
            ])
            @include('common.errors')
        @endslot
    @endcomponent
@stop
