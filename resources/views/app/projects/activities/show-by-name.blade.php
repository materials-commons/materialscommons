@extends('layouts.app')

@section('pageTitle', "{$project->name} - Processes")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.activities.index', $project))--}}

@section('content')
    <h3>Show Process Type: {{$name}}</h3>
    <div class="row ms-1">
        @foreach($activities as $activity)
            <div class="col-lg-3 col-md-5 col-sm-5 ms-2 bg-grey-9 mt-2">
                @include('partials.activities.activity-card-with-entities', ['activity' => $activity])
            </div>
        @endforeach
    </div>
@stop
