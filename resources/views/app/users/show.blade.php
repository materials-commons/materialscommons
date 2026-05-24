@extends('layouts.app')

@section('pageTitle', 'Show Project User')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3 class="text-center">User {{$user->name}}</h3>
    <br/>

    @include('partials.show_user')
@stop
