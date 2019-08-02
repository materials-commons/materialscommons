@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Files
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
