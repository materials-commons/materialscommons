@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3>Transfer Request</h3>
    <x-transfer-requests.show-standard-details :transfer-request="$transferRequest"/>
    @if(!is_null($transferRequest->globusTransfer))
        <x-transfer-requests.show-globus-details :globus-transfer="$transferRequest->globusTransfer"/>
    @endif
@stop
