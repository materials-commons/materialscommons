@extends('layouts.app')

@section('pageTitle', 'Teams')

@section('content')
    @component('components.card')
        @slot('header')
            Teams
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
