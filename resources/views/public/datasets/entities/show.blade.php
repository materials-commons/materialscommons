@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.entities.show', $dataset, $entity))

@section('content')
    @component('components.card')
        @slot('header')
            Sample: {{$entity->name}}
        @endslot

        @slot('body')
            @component('components.items-details', ['item' => $entity])
            @endcomponent
            <br>
            <h4>Sample Attributes</h4>
            <hr>
            @include('partials.attributes_table', ['attributes' => $attributes])
        @endslot
    @endcomponent
@stop