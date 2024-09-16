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
            {{--            <x-datahq.mql-controls/>--}}
            {{--            <hr>--}}
            <div class="mt-2xxx">
                {{--                <x-datahq.view-controls :project="$project"/>--}}
                {{--                <br/>--}}
                @if(hasTabParam('all-samples'))
                    <x-datahq.sampleshq.all-samples-view :project="$project"/>
                @elseif(hasTabParam("fv1"))
                    <x-datahq.mql-controls/>
                    <x-datahq.sampleshq.all-samples-view :project="$project"/>
                @elseif(Request::routeIs('projects.datahq.sampleshq.activity-attributes.filters'))
                    @include('app.projects.datahq.sampleshq.pages.activity-attribute-filters')
                @elseif(Request::routeIs('projects.datahq.sampleshq.activities.filters'))
                    @include('app.projects.datahq.sampleshq.pages.activity-filters')
                @endif
            </div>
        </x-slot:body>
    </x-card>
@stop
