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
            @if(!is_null($globusTransfer))
                <p>
                    Your globus request has been set up. Click
                    <a href="{{$globusTransfer->globus_url}}" target="_blank">here</a>
                    to go to the Globus web interface.
                </p>
            @else
                <p>There is no active globus transfer</p>
            @endif
        </x-slot>
    </x-card>
@endsection