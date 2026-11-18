@extends('layouts.app')

@section('pageTitle', "{$project->name} - Attach Workflows")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <livewire:experiments.workflow-attachment
        :project="$project"
        :experiment="$experiment"
    />
@stop
