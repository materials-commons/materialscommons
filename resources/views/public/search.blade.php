@extends('layouts.app')

@section('pageTitle', 'Search Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Search results for {{$search}}
        @endslot

        @slot('body')
            @include('partials.search')
        @endslot
    @endcomponent
@stop