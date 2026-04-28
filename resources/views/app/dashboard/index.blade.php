@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@if (Request::routeIs('dashboard'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard'))
@elseif(Request::routeIs('dashboard.projects.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.projects.show'))
@elseif(Request::routeIs('dashboard.published-datasets.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.published-datasets.show'))
@endif

@section('content')
    @include('app.dashboard.tabs.tabs')
    <br>
    @if (Request::routeIs('dashboard.projects.show'))
        @if(isInBeta('dashboard-charts'))
            @include('app.dashboard.tabs.projects-v2')
        @else
            @include('app.dashboard.tabs.projects')
        @endif
    @elseif (Request::routeIs('dashboard.published-datasets.show'))
        <x-table-container>
            @if(isInBeta('dashboard-charts'))
                @include('app.dashboard.tabs.published-datasets-v2')
            @else
                @include('app.dashboard.tabs.published-datasets')
            @endif
        </x-table-container>
    @elseif (Request::routeIs('dashboard.projects.archived.index'))
        <x-table-container>
            @include('app.dashboard.tabs.archived-projects')
        </x-table-container>
    @elseif (Request::routeIs('dashboard.projects.trash.index'))
        <x-table-container>
            @include('app.dashboard.tabs.deleted-projects')
        </x-table-container>
    @elseif(Request::routeIs('dashboard.admin.mcfs.index'))
        @include('app.admin.tabs.mcfs-index')
    @endif
@endsection
