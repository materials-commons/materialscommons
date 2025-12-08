@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Create folder in directory {{$directory->name}}</h3>
    <br/>

    @include('app.projects.folders._create', [
        'storeDirectoryRoute' => route('projects.datasets.create-data.store-directory', [$project, $dataset]),
        'cancelRoute' => route('projects.datasets.files.edit', [$project, $dataset, $directory])
    ])
    @include('common.errors')
@stop
