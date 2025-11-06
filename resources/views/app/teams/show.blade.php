@extends('layouts.app')

@section('pageTitle', 'Show Team')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3 class="text-center">Team: {{$team->name}}</h3>
    <br/>

    <x-show-standard-details :item="$team"/>

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
@stop

