@extends('layouts.app')

@section('pageTitle', "{$project->name} - Add URL")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Add URL in directory {{$directory->name}}
        @endslot

        @slot('body')
            @include('app.projects.folders._add-url', [
                'storeUrlRoute' => route('projects.folders.store-url', [$project, $directory, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg]),
                'cancelRoute' => route('projects.folders.show', [$project, $directory, 'destdir' => $destDir, 'destproj' => $destProj, 'arg' => $arg])
            ])
            @include('common.errors')
        @endslot
    @endcomponent
@stop
