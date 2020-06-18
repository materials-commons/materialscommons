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
            @component('components.item-details', ['item' => $entity])
            @endcomponent

            <div class="row ml-1">
                @foreach($activities as $activity)
                    <div class="col-lg-3 col-md-5 col-sm-5 ml-2 bg-grey-9 mt-2">
                        @include('public.datasets.entities.activity-card', ['activity' => $activity])
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@stop