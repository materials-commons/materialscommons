@extends('layouts.app')

@section('pageTitle', 'UHCSDB')

@section('nav')
    @include('layouts.navs.public')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render(Route::getCurrentRoute()->getName(), $dataset))--}}

@section('content')
    <h3>UHCSDB Here</h3>
    <div style="height:750px">
        <iframe src="http://localhost:9000" width="100%" height="100%"></iframe>
    </div>
@stop