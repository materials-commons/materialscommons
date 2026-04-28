@extends('layouts.app')

@section('pageTitle', 'Visus Dataset')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <div style="height:750px">
        <iframe src="{{$visusDatasetUrl}}" width="100%" height="100%"></iframe>
    </div>
@stop
