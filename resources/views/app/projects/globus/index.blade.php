@extends('layouts.app')

@section('pageTitle', 'Project Globs Uploads')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Globus Uploads
            <a class="action-link float-right" href="#" onclick="refresh()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </a>
        @endslot

        @slot('body')
            @include('partials.globus_uploads', ['showProject' => false])
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            function refresh() {
                window.location.replace("{{route('projects.globus.status', [$project])}}");
            }

            $(document).ready(() => setInterval(refresh, 10000));
        </script>
    @endpush
@stop
