@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Dataset Details Step
        @endslot

        @slot('body')
            dataset details
        @endslot
    @endcomponent
@stop