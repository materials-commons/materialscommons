@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Transfer Request
        </x-slot>
        <x-slot name="body">
            <x-transfer-requests.show-standard-details :transfer-request="$transferRequest"/>
            <x-transfer-requests.show-globus-details :globus-transfer="$transferRequest->globusTransfer"/>
        </x-slot>
    </x-card>
@stop