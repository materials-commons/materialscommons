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
            <h4 class="mb-2">Outstanding Globus Uploads</h4>
            @include('partials.globus_uploads', ['showProject' => true])
        @endslot
    @endcomponent
@endsection
