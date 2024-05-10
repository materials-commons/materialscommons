@extends('layouts.app')

@section('pageTitle', 'Admin Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <x-log-viewer :load-log-route="route('admin.dashboard.mcfs.show-log')"
                  :search-log-route="route('admin.dashboard.mcfs.search-log')"/>
@stop