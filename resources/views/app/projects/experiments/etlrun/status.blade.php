@extends('layouts.app')

@section('pageTitle', "{$project->name} - Import Status")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))--}}

@section('content')
@stop
