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
            @if($projectsCount == 0)
                <p>
                    It doesn't appear you have any projects. In Materials Commons all data is stored in a project. In
                    this
                    step you can give us the name of a project, and we will do all the setup. If you aren't sure what
                    name
                    to use then you can give it the same name you would for your published data.
                </p>

                <p>
                    The steps after this will take you through uploading and publishing your data.
                </p>
            @else
                <p>
                    After you've created a project to store your data in you will be taken to the steps for creating
                    your dataset, uploading files, and publishing.
                </p>
            @endif

            @include('partials._create_project', [
                'createProjectRoute' => route('public.publish.wizard.store_project'),
                'cancelRoute' => route('welcome')
            ])
        @endslot
    @endcomponent
@stop