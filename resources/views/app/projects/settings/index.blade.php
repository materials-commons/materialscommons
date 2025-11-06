@extends('layouts.app')

@section('pageTitle', "{$project->name} - Settings")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Settings</h3>
    <br/>
@stop
