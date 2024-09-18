@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')

    <x-card>
        <x-slot:header>
            Data Explorer
            <x-datahq.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            <div>
                @include('app.projects.datahq.tabs')
                <div class="mt-2">
                    @if(hasTabParam('samples'))
                        <x-projects.samples.samples-table :project="$project"/>
                    @elseif(hasTabParam('computations'))
                        <x-projects.computations.computations-table :project="$project"/>
                    @elseif(hasTabParam('processes'))
                        <x-projects.processes.processes-table :project="$project"/>
                    @elseif(hasTabParam('sampleattrs'))
                        <x-projects.samples.sample-attributes-table :project="$project"/>
                    @elseif(hasTabParam('computationattrs'))
                        <x-projects.computations.computation-attributes-table :project="$project"/>
                    @elseif(hasTabParam('processattrs'))
                        <x-projects.processes.process-attributes-table :project="$project"/>
                    @endif
                </div>
            </div>
        </x-slot:body>
    </x-card>
@stop
