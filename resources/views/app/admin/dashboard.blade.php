@extends('layouts.app')

@section('pageTitle', 'Admin Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <x-card>
        <x-slot:header>Admin Dashboard</x-slot:header>
        <x-slot:body>
            {{--            <a href="/app/log-viewer">View Laravel Logs</a>--}}
            @include('app.admin.tabs.tabs')
            <br/>
            @if (Request::routeIs('admin.dashboard.mcfs.index'))
                <x-card-container>
                    @include('app.admin.tabs.mcfs-index')
                </x-card-container>
            @endif
        </x-slot:body>
    </x-card>
@stop
