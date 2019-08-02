@extends('layouts.app')

@section('pageTitle', 'Processes')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Processes
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
