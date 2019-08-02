@extends('layouts.app')

@section('pageTitle', 'Teams')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Teams
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
