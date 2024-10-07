@props(['breadcrumbs' => null, 'pageTitle', 'content', 'project'])
<div>
    @extends('layouts.app')

    @section('pageTitle')
        {{$pageTitle}}
    @endsection

    @if (!is_null('breadcrumbs'))
        @section('breadcrumbs')
            {{$breadcrumbs}}
        @endsection
    @endif

    @section('nav')
        @include('layouts.navs.app.project')
    @stop

    @section('content')
        {{$content}}
    @endsection
</div>
