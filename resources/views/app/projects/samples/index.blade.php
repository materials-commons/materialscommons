@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Samples
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
