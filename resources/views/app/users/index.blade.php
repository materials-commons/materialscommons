@extends('layouts.app')

@section('pageTitle', 'Users')

@section('content')
    @component('components.card')
        @slot('header')
            Users
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop
