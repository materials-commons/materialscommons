@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render('public.datasets.entities.show', $dataset, $entity))

@section('content')

    <h3 class="text-center">Sample: {{$entity->name}}</h3>

    <a class="float-end action-link" href="#"
       onclick="window.location.replace('{{route('public.datasets.entities.show', [$dataset, $entity])}}')">
        <i class="fas fa-object-group me-2"></i>Group By Process Type
    </a>
    <br/>
    <br/>

    <x-show-standard-details :item="$entity"/>

    <div class="row ms-1">
        @foreach($activities as $activity)
            <div class="col-lg-5 col-md-10 col-sm-10 ms-2 mt-2 mb-2 white-box">
                @include('public.datasets.entities.activity-card', ['activity' => $activity])
            </div>
        @endforeach
    </div>
@stop
