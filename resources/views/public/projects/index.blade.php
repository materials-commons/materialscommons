@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Public Data Projects
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
