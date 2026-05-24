@extends('layouts.app')

@section('pageTitle', "{$project->name} - Query Editor")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    <h3 class="text-center">Query Editor</h3>
    <livewire:datahq.queryhq.mql-query-editor />
@stop
