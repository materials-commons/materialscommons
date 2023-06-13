@extends('layouts.app')

@section('pageTitle', "{$project->name} - Project Data Dictionary")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.datasets.index', $project))--}}

@section('content')
    @include('partials.datadictionary._show', [
        'name' => $project->name,
    ])
@stop

