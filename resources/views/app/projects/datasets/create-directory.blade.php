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
                'storeDirectoryRoute' => route('projects.datasets.create-data.store-directory', [$project, $dataset]),
                'cancelRoute' => route('projects.datasets.files.edit', [$project, $dataset, $directory])
            ])
            @include('common.errors')
        @endslot
    @endcomponent
@stop
