@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Show Dataset
        @endslot

        @slot('body')
            <div>{{$dataset->name}}</div>
        @endslot
    @endcomponent
@stop