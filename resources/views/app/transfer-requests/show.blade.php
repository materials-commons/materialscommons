@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Transfer Request
        </x-slot>
        <x-slot name="body">
            <x-card-container>
                <x-transfer-requests.show-standard-details :transfer-request="$transferRequest"/>
                @if(!is_null($transferRequest->globusTransfer))
                    <x-transfer-requests.show-globus-details :globus-transfer="$transferRequest->globusTransfer"/>
                @endif
            </x-card-container>
        </x-slot>
    </x-card>
@stop
