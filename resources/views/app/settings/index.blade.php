@extends('layouts.app')

@section('pageTitle', 'Settings')

@section('content')
    @component('components.card')
        @slot('header')
            Settings
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
