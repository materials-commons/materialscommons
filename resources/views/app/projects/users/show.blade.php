@extends('layouts.app')

@section('pageTitle', 'Show Project User')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Show User in project {{$project->name}}
        @endslot

        @slot('body')
            @include('partials.show_user')
        @endslot
    @endcomponent
@stop