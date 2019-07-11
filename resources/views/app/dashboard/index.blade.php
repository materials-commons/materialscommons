@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('content')
    @component('components.card')
        @slot('header')
            Dashboard
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@endsection