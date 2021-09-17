@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Data Explorer

        @endslot

        @slot('body')
            <x-data-explorer.explorer/>
            <div class="mt-2">
                @include('app.projects.data-explorer.tabs._tabs')
                <div class="mt-2">
                    @if(Request::routeIs('projects.data-explorer.samples'))
                        @include('app.projects.data-explorer.tabs._samples')
                    @elseif(Request::routeIs('projects.data-explorer.sample-details'))
                        @include('app.projects.data-explorer.tabs._sample_details')
                    @elseif(Request::routeIs('projects.data-explorer.sample-files'))
                        @include('app.projects.data-explorer.tabs._files')
                    @elseif(Request::routeIs('projects.data-explorer.sample-attributes'))
                        @include('app.projects.data-explorer.tabs._sample_attributes')
                    @elseif(Request::routeIs('projects.data-explorer.process-attributes'))
                        @include('app.projects.data-explorer.tabs._process_attributes')
                    @elseif(Request::routeIs('projects.data-explorer.data-source'))
                        @include('app.projects.data-explorer.tabs._data_source')
                    @endif
                </div>
            </div>
        @endslot
    @endcomponent
@stop
