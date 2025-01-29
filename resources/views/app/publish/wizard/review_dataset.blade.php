@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <x-card>
        <x-slot:header>
            Review Dataset
        </x-slot:header>
        >

        <x-slot:body>
            Review Dataset
        </x-slot:body>
    </x-card>>
@stop
