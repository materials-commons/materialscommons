@extends('layouts.app')

@section('pageTitle', "{$project->name} - Monitor Globus Transfer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Monitor Globus Transfer</h3>
    <br/>
    <x-projects.globus.monitor.globus-transfer :globus-request="$globusRequest"/>
@endsection
