@extends('layouts.app')

@section('pageTitle', 'Processes')

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
            <ul>
                @foreach($activities as $activity)
                    <li>
                        {{$activity->name}}
                        <ul>
                            @foreach($activity->entities as $entity)
                                <li>{{$entity->name}}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endslot
    @endcomponent
@stop