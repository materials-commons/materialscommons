@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Folder")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Create folder in directory {{$directory->name}}</h3>
    <br/>

    @include('app.projects.folders._create', [
        'storeDirectoryRoute' => route('projects.folders.store', [$project, $directory, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg]),
        'cancelRoute' => route('projects.folders.show', [$project, $directory, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg])
    ])
    @include('common.errors')
@stop
