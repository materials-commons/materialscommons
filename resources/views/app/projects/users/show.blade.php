@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show User")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Show User in project {{$project->name}}</h3>
    <br/>

    @include('partials.show_user')
@stop
