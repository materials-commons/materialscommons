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
            <a class="float-right action-link" href="#"
               onclick="window.location.replace('{{route('public.datasets.entities.show-spread', [$dataset, $entity])}}')">
                <i class="fas fa-object-ungroup mr-2"></i>Ungroup Processes
            </a>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$entity"/>

            <div class="row ml-1">
                @foreach($activityTypes as $activityType)
                    <div class="col-lg-5 col-md-10 col-sm-10 ml-2 mt-2 rounded border-blue border">
                        @include('public.datasets.entities.activity-type-card', [
                            'activityType' => $activityType,
                            'files' => $filesByActivityType->get($activityType->name, []),
                            'attributes' => $attributesByActivityType->get($activityType->name, []),
                            'measurements' => $measurementsByActivityType->get($activityType->name, []),
                        ])
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@stop