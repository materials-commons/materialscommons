@extends('layouts.project')

@section('pageTitle', 'Processes')

@section('content')
    @component('components.card')
        @slot('header')
            Processes
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop