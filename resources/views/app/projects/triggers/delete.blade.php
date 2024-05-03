@extends('layouts.app')

@section('pageTitle', 'Delete Trigger')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>Delete Trigger {{$trigger->name}}</x-slot:header>
        <x-slot:body></x-slot:body>
    </x-card>
@stop