@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@if (Request::routeIs('dashboard'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard'))
@elseif(Request::routeIs('dashboard.projects.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.projects.show'))
@elseif(Request::routeIs('dashboard.published-datasets.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.published-datasets.show'))
@endif

@section('content')
    <x-card>
        <x-slot name="header">Site Statistics</x-slot>
        <x-slot name="body">
            <div style="width:75%;">
                {!! $usersChart->render() !!}
            </div>
            <br/>
            <br/>
            <div style="width:75%">
                {!! $projectsChart->render() !!}
            </div>
            <br/>
            <br/>
            <div style="width:75%">
                {!! $publishedDatasetsChart->render() !!}
            </div>

            <br/>
            <br/>
            <div style="width:75%">
                {!! $entitiesChart->render() !!}
            </div>

            <br/>
            <br/>
            <div style="width:75%">
                {!! $activitiesChart->render() !!}
            </div>

            <br/>
            <br/>
            <div style="width:75%">
                {!! $attributesChart->render() !!}
            </div>

            {{--            <br/>--}}
            {{--            <br/>--}}
            {{--            <div style="width:75%">--}}
            {{--                {!! $filesUploadedChart->render() !!}--}}
            {{--            </div>--}}

        </x-slot>
    </x-card>
@endsection