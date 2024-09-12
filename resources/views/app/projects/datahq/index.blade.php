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
                @include('app.projects.datahq.pages.tabs')
                <div class="mt-2">
                    @if(hasTabParam('samples'))
                        <x-projects.samples.samples-table :project="$project"/>
                    @elseif(hasTabParam('computations'))
                        <x-projects.computations.computations-table :project="$project"/>
                    @elseif(hasTabParam('processes'))
                        Processes table here
                    @elseif(hasTabParam('sampleattrs'))
                        Sample Attributes here
                    @elseif(hasTabParam('computationattrs'))
                        Computation Attributes here
                    @elseif(hasTabParam('processattrs'))
                        Process Attributes here
                    @else
                        {{-- default to samples if no param passed in --}}
                        <x-projects.samples.samples-table :project="$project"/>
                    @endif
                </div>
            </div>
        </x-slot:body>
    </x-card>
@stop
