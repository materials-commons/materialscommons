@extends('layouts.app')

@section('pageTitle', "{$project->name} - DataHQ")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}
