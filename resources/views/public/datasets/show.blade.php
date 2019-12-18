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
            @component('components.items-details', ['item' => $dataset])
                @slot('top')
                    <div class="form-group">
                        <label for="authors">Authors and Affiliations</label>
                        <input class="form-control" value="{{$dataset->authors}}" id="authors" type="text" readonly>
                    </div>
                @endslot


                <span class="ml-4">Published:
                    @isset($dataset->published_at)
                        {{$dataset->published_at->diffForHumans()}}
                    @else
                        Not Published
                    @endisset
                </span>

                @slot('bottom')
                    <div class="form-group">
                        <label for="doi">DOI</label>
                        <input class="form-control" id="doi" type="text" value="{{$dataset->doi}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="license">License</label>
                        <input class="form-control" id="license" type="text" value="{{$dataset->license}}" readonly>
                    </div>
                @endslot
            @endcomponent

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
            @elseif(Request::routeIs('public.datasets.communities.*'))
                @include('public.datasets.tabs.communities')
            @elseif (Request::routeIs('public.datasets.comments*'))
                @include('public.datasets.tabs.comments-tab')
            @endif

        @endslot
    @endcomponent
@stop
