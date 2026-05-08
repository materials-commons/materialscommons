@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render(Route::getCurrentRoute()->getName(), $dataset))

@section('content')
    <div class="container-fluid px-3 px-xl-4 py-3">
        <x-datasets.hero
            :dataset="$dataset"
            :user-projects="$userProjects ?? collect()"
            :has-notifications-for-dataset="$hasNotificationsForDataset ?? false"
        />

        @include('public.datasets.tabs.tabs')

        <div class="mt-3">
            @if (Request::routeIs('public.datasets.overview*'))
                @include('public.datasets.tabs.overview-tab')
            @elseif (Request::routeIs('public.datasets.workflows*'))
                @include('public.datasets.tabs.workflows-tab')
            @elseif (Request::routeIs('public.datasets.entities*'))
                @include('public.datasets.tabs.entities-tab')
            @elseif (Request::routeIs('public.datasets.files*'))
                @include('public.datasets.tabs.files-tab')
            @elseif(Request::routeIs('public.datasets.folders*'))
                @include('public.datasets.tabs.folders-tab')
            @elseif(Request::routeIs('public.datasets.communities.*'))
                @include('public.datasets.tabs.communities')
            @elseif (Request::routeIs('public.datasets.comments*'))
                @include('public.datasets.tabs.comments-tab')
            @endif
        </div>
    </div>

    @include("public.datasets.cite-dataset-modal")

    @isset($dsAnnotation)
        @push('googleds')
            <script type="application/ld+json">
                @json($dsAnnotation, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endpush
    @endisset

    @php
        $zipLoginRoute = route('login-for-dataset-zipfile-download', [$dataset]);
        $zipCreateAccountRoute = '';

        $globusLoginRoute = route('login-for-dataset-globus-download', [$dataset]);
        $globusCreateAccountRoute = '';
    @endphp

    @include('app.dialogs.ds-download-dialog', [
            'dialogId' => 'ds-download-zip',
            'loginRoute' => $zipLoginRoute,
            'createAccountRoute' => $zipCreateAccountRoute,
    ])

    @include('app.dialogs.ds-download-dialog', [
            'dialogId' => 'ds-download-globus',
            'loginRoute' => $globusLoginRoute,
            'createAccountRoute' => $globusCreateAccountRoute,
    ])
@stop
