@extends('layouts.project')

@section('pageTitle', 'Samples')

@section('content')
    @component('components.card')
        @slot('header')
            Samples
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop