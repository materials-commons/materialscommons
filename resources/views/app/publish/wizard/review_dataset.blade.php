@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Review Dataset
        @endslot

        @slot('body')
            Review Dataset
        @endslot
    @endcomponent
@stop
