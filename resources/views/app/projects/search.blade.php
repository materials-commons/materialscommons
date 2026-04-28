@extends('layouts.app')

@section('pageTitle', "{$project->name} - Search Results")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))

@section('content')
    <h3 class="text-center">Search results for {{$search}}</h3>
    <br/>

    @include('partials.search')
@stop
