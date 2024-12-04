@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    <livewire:datahq.data-explorer :project="$project" :context="$context" :view="$view" :tab="$tab"
                                   :subview="$subview"/>
@stop
