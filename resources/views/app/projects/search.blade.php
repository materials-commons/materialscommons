@extends('layouts.app')

@section('pageTitle', "{$project->name} - Search Results")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Search results for {{$search}}
        @endslot

        @slot('body')
            @include('partials.search')
        @endslot
    @endcomponent
@stop
