@extends('layouts.app')

@section('pageTitle', "{$project->name} - Monitor Globus Transfer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Monitor Globus Transfer
        </x-slot>
        <x-slot name="body">
            <x-projects.globus.monitor.globus-transfer :globus-request="$globusRequest"/>
        </x-slot>
    </x-card>
@endsection
