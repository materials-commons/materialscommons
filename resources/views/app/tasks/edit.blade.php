@extends('layouts.app')

@section('pageTitle', 'Edit')

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