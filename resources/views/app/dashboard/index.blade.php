@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Dashboard
        @endslot

        @slot('body')
            @include('app.dashboard.tabs.tabs')
            <br>
            @if (Request::routeIs('dashboard.projects.show'))
                @include('app.dashboard.tabs.projects')
            @elseif (Request::routeIs('dashboard.published-datasets.show'))
                @include('app.dashboard.tabs.published-datasets')
            @elseif (Request::routeIs('dashboard.data-dictionary.show'))
                @include('app.dashboard.tabs.data-dictionary')
            @endif
        @endslot
    @endcomponent
@endsection
