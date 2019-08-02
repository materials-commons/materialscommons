@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Dashboard
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@endsection
