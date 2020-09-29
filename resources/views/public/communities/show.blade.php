@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @auth
        @if(auth()->id() == $community->owner_id)
            @php
                $editCommunityRoute = route('communities.edit', [$community]);
            @endphp
        @else
            @php
                $editCommunityRoute = null;
            @endphp
        @endif
        @include('partials.communities._show', [
            'doneRoute' => route('public.communities.index'),
            'showRouteName' => 'public.communities.show',
            'datasetsRouteName' => 'public.communities.datasets.index',
            'filesRouteName' => 'public.communities.files.show',
            'linksRouteName' => 'public.communities.links.show',
            'datasetRouteName' => 'public.datasets.show',
            'editCommunityRoute' => $editCommunityRoute,
        ])
    @endauth

    @guest
        @include('partials.communities._show', [
            'doneRoute' => route('public.communities.index'),
            'showRouteName' => 'public.communities.show',
            'datasetsRouteName' => 'public.communities.datasets.index',
            'filesRouteName' => 'public.communities.files.show',
            'linksRouteName' => 'public.communities.links.show',
            'datasetRouteName' => 'public.datasets.show',
        ])
    @endguest
@stop