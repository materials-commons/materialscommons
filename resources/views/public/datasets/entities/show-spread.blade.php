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

            <a class="float-end action-link" href="#"
               onclick="window.location.replace('{{route('public.datasets.entities.show', [$dataset, $entity])}}')">
                <i class="fas fa-object-group mr-2"></i>Group By Process Type
            </a>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$entity"/>

            <div class="row ml-1">
                @foreach($activities as $activity)
                    <div class="col-lg-5 col-md-10 col-sm-10 ml-2 mt-2 mb-2 white-box">
                        @include('public.datasets.entities.activity-card', ['activity' => $activity])
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@stop
