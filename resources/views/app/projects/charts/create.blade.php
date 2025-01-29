@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    <x-card>
        <x-slot:header>
            Charts
        </x-slot:header>

        <x-slot:body>
            <x-charts.create-chart :project="$project"/>
        </x-slot:body>
    </x-card>
@stop
