@extends('layouts.app')

@section('pageTitle', "{$project->name} - Processes")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.activities.index', $project))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Show Process Type: {{$name}}
        @endslot

        @slot('body')
            <div class="row ml-1">
                @foreach($activities as $activity)
                    <div class="col-lg-3 col-md-5 col-sm-5 ml-2 bg-grey-9 mt-2">
                        @include('partials.activities.activity-card-with-entities', ['activity' => $activity])
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@stop
