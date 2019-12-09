@extends('layouts.app')

@section('pageTitle', 'Settings')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Settings
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
