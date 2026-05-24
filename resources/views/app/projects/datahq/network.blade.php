@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    {{--    <x-datahq.network-visualization :project="$project"/>--}}
    <div>
        <livewire:datahq.networkhq.index :project="$project"/>
    </div>
@stop
