@extends('layouts.app')

@section('pageTitle', 'Admin Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <x-log-viewer :load-log-route="route('admin.dashboard.mcfs.show-log')"
                  :set-log-level-route="route('admin.dashboard.mcfs.log.set-log-level')"
                  :search-log-route="route('admin.dashboard.mcfs.search-log')"/>
@stop