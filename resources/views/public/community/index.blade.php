@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Data Communities
        @endslot

        @slot('body')
            @include('public.community._communities_table')
        @endslot
    @endcomponent
@stop

