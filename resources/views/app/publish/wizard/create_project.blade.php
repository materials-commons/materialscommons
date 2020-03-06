@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Create Project Step
        @endslot

        @slot('body')
            <p>
                It doesn't appear you have any projects. In Materials Commons all data is stored in a project. In this
                step you can give us the name of a project, and we will do all the setup. If you aren't sure what name
                to use then you can give it the same name you would for your published data.
            </p>

            <p>
                The steps after this will take you through uploading and publishing your data.
            </p>
            @include('partials._create_project', [
                'createProjectRoute' => route('projects.datasets.create', [$project]),
                'cancelRoute' => ''
            ])
        @endslot
    @endcomponent
@stop