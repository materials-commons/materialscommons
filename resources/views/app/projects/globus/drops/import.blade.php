@extends('layouts.app')

@section('pageTitle', "{$project->name} - Import Drop")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    <h3 class="text-center">Import Drop</h3>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <livewire:projects.globus-drops.import-drop :project="$project" :drop="$drop"/>
        </div>
    </div>
@stop
