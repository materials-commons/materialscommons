@extends('layouts.app')

@section('pageTitle', 'Edit')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit
        @endslot

        @slot('body')
            edit
        @endslot
    @endcomponent
@endsection
