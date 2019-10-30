@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$dataset->name}}</dd>
                    <dt class="col-sm-2">License</dt>
                    <dd class="col-sm-10"><a href="{{$dataset->license_link}}">{{$dataset->license}}</a></dd>
                    <dt class="col-sm-2">Authors</dt>
                    <dd class="col-sm-10">{{$dataset->authors}}</dd>
                    <dt class="col-sm-2">Institution</dt>
                    <dd class="col-sm-10">{{$dataset->institution}}</dd>
                    <dt class="col-sm-2">Funding</dt>
                    <dd class="col-sm-10">{{$dataset->funding}}</dd>
                    <dt class="col-sm-2">Published</dt>
                    <dd class="col-sm-10">{{$dataset->published_at->diffForHumans()}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$dataset->description}}</p>
            </div>

            <br>

            @include('public.datasets.tabs.tabs')

            <br>

            @if (Request::routeIs('public.datasets.show*'))
                @include('public.datasets.tabs.workflows-tab')
            @elseif (Request::routeIs('public.datasets.entities*'))
                @include('public.datasets.tabs.entities-tab')
            @elseif (Request::routeIs('public.datasets.activities*'))
                @include('public.datasets.tabs.activities-tab')
            @elseif (Request::routeIs('public.datasets.files*'))
                @include('public.datasets.tabs.files-tab')
            @endif

        @endslot
    @endcomponent
@stop
