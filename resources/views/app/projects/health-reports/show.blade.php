@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')
{{--    <x-card>--}}
{{--        <x-slot:header>--}}
{{--            <h2>Health Report for {{$project->name}} on {{$date}}</h2>--}}
{{--        </x-slot:header>--}}
{{--        <x-slot:body>--}}
            <x-projects.health-reports.show :health-report="$healthReport"/>
{{--        </x-slot:body>--}}
{{--    </x-card>--}}
@endsection
