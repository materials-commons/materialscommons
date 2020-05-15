@extends('layouts.app')

@section('pageTitle', 'Show Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @include('partials.communities._show', [
        'editCommunityRoute' => route('communities.edit', [$community]),
        'doneRoute' => route('communities.index'),
        'showRouteName' => 'communities.show',
        'practicesRouteName' => 'communities.practices.show',
    ])
@stop