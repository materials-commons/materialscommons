@extends('layouts.app')

@section('pageTitle', 'Admin Dashboard')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3>Admin Dashboard</h3>
    {{--            <a href="/app/log-viewer">View Laravel Logs</a>--}}
    @include('app.admin.tabs.tabs')
    <br/>
    @if (Request::routeIs('admin.dashboard.mcfs.index'))
        @include('app.admin.tabs.mcfs-index')
    @endif
@stop
