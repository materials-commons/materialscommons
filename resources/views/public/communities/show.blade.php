@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @include('partials.communities._show', [
        'doneRoute' => route('public.communities.index'),
        'showRouteName' => 'public.communities.show',
        'practicesRouteName' => 'public.communities.practices.show',
        'datasetRouteName' => 'public.datasets.show',
    ])
@stop