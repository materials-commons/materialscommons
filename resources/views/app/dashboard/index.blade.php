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
        <x-slot name="header">
            <span id="dashboard-intro">Dashboard</span>
        </x-slot>
        <x-slot name="body">
            @include('app.dashboard.tabs.tabs')
            <br>
            @if (Request::routeIs('dashboard.projects.show'))
                @include('app.dashboard.tabs.projects')
            @elseif (Request::routeIs('dashboard.published-datasets.show'))
                @include('app.dashboard.tabs.published-datasets')
            @elseif (Request::routeIs('dashboard.projects.archived.index'))
                @include('app.dashboard.tabs.archived-projects')
            @elseif (Request::routeIs('dashboard.projects.trash.index'))
                @include('app.dashboard.tabs.deleted-projects')
            @elseif(Request::routeIs('dashboard.admin.mcfs.index'))
                @include('app.admin.tabs.mcfs-index')
            @endif
        </x-slot>
    </x-card>
@endsection
