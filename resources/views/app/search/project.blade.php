@extends('layouts.app')

@section('pageTitle', "{$project->name} - Search")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <livewire:search.project-search-results :project="$project" />
@stop
