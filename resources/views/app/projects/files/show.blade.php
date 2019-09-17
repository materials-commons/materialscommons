@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}}
        @endslot

        @slot('body')
            File stuff here
        @endslot
    @endcomponent
@endsection