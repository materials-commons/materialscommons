@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')
    <x-projects.health-reports.show :health-report="$healthReport"/>
@endsection
