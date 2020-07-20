@extends('layouts.app')

@section('pageTitle', 'Show Team')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Team: {{$team->name}}
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $team])
            @endcomponent

            <hr>
            <br>

            @include('app.teams.tabs.tabs')

            <br>

            @if (Request::routeIs('teams.show'))
                @include('app.teams.tabs.projects')
            @elseif(Request::routeIs('teams.members.show'))
                @include('app.teams.tabs.members')
            @elseif (Request::routeIs('teams.admins.show'))
                @include('app.teams.tabs.admins')
            @endif

        @endslot
    @endcomponent
@stop

