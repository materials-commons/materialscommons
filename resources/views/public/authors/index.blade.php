@extends('layouts.public')

@section('pageTitle', 'Public Data')

@section('content')
    @component('components.card')
        @slot('header')
            Public Data Authors
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@stop