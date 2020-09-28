@extends('layouts.app')

@section('pageTitle', 'View Process')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.activities.show', $dataset, $activity))

@section('content')
    @component('components.card')
        @slot('header')
            Process: {{$activity->name}}
        @endslot

        @slot('body')
            <x-show-standard-details :item="$activity"/>
            <br>

            <h4>Process Attributes</h4>
            <hr>
            @include('partials.attributes_table', ['attributes' => $activity->attributes])
        @endslot
    @endcomponent
@stop