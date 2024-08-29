@extends('layouts.app')

@section('pageTitle', "{$dataset->name} - Show Attribute")

@section('nav')
    @include('layouts.navs.public')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.datasets.index', $project))--}}

@section('content')
    @include('partials.attributes._show')
@stop