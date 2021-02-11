@extends('layouts.app')

@section('pageTitle', 'Show Globus Upload')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Globus Transfer
        </x-slot>
        <x-slot name="body">
            <p>
                Your globus request has been set up. Click
                <a href="{{$globusRequest->globus_url}}" target="_blank">here</a>
                to go to the Globus web interface.
            </p>
        </x-slot>
    </x-card>
@endsection