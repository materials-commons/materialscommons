@extends('layouts.public')

@section('pageTitle', 'Public Data')

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
        @endslot

        @slot('body')
            Details for dataset here
        @endslot
    @endcomponent
@stop