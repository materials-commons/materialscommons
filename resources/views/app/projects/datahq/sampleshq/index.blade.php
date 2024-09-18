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
                <x-datahq.explorer.tabs :project="$project" :state-service="'sampleshq'"/>
                <br/>
                <div>
                    <x-datahq.sampleshq.tab-view-handler :project="$project"/>
                </div>
            </div>
        </x-slot:body>
    </x-card>
@stop
