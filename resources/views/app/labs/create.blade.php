@extends('layouts.app')

@section('pageTitle', 'Create Lab')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Lab
        @endslot

        @slot('body')
        @endslot
    @endcomponent
    @include('common.errors')
@stop
