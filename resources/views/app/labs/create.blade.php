@extends('layouts.app')

@section('pageTitle', 'Create Lab')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3 class="text-center">Create Lab</h3>
    <br/>
    @include('common.errors')
@stop
