@extends('layouts.app')

@section('pageTitle', 'Search Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <h3 class="text-center">Search results for {{$search}}</h3>
    <br/>

    @include('partials.search')
@stop
