@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Attribute")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.datasets.index', $project))--}}

@section('content')
    @include('partials.attributes._show')
@stop
