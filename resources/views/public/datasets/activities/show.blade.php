@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.activities.show', $dataset, $activity))

@section('content')
    <h3 class="text-center">Process: {{$activity->name}}</h3>
    <br/>

    <x-show-standard-details :item="$activity"/>
    <br>

    <h4>Process Attributes</h4>
    <hr>
    @include('partials.attributes_table', ['attributes' => $activity->attributes])
@stop
