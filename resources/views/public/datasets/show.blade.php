@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('breadcrumbs', Breadcrumbs::render(Route::getCurrentRoute()->getName(), $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
            <div class="dropdown float-end me-4">
                @if(!blank($dataset->doi))
                    <a class="action-link me-3 cursor-pointer" data-bs-toggle="modal" href="#cite-dataset-modal"><i
                            class="fas fa-quote-left me-1"></i>Cite Dataset</a>
                @endif
                @auth
                    {{--                @if($dataset->canEdit())--}}
                    {{--                    <a class="action-link float-end me-4"--}}
                    {{--                       href="{{route('projects.datasets.edit', [$dataset->project_id, $dataset->id, 'public' => true])}}">--}}
                    {{--                        <i class="fas fa-edit me-2"></i>Edit--}}
                    {{--                    </a>--}}
                    {{--                @endif--}}

                    <a class="action-link dropdown-toggle" href="#" id="projectsDropdown" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-import me-2"></i>Import Into Project
                    </a>
                    <div class="dropdown-menu" aria-labelledby="projectsDropdown">
                        @foreach($userProjects as $project)
                            @if($project->owner_id == auth()->id() && $project->id != $dataset->project_id)
                                <a class="dropdown-item td-none"
                                   href="{{route('public.datasets.import-into-project', [$dataset, $project])}}">
                                    {{$project->name}}
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @if($hasNotificationsForDataset)
                        <a class="action-link ms-3"
                           href="#"
                           id="notification"
                           data-bs-toggle="tooltip"
                           title="Stop notifications on dataset"
                           hx-get="{{route('public.datasets.notifications.unmark-for-notification', [$dataset])}}"
                           hx-target="#notification"
                           hx-swap="outerHTML">
                            <i class='fa-fw fas fa-bell yellow-4'></i>
                        </a>
                    @else
                        <a class="action-link ms-3"
                           href="#"
                           id="notification"
                           data-bs-toggle="tooltip"
                           title="Get notified when dataset is updated"
                           hx-get="{{route('public.datasets.notifications.mark-for-notification', [$dataset])}}"
                           hx-target="#notification"
                           hx-swap="outerHTML">
                            <i class="fas fa-fw fa-bell-slash"></i>
                        </a>
                    @endif
                    {{--                @if(auth()->user()->hasCommunities())--}}
                    {{--                    <div class="dropdown float-end me-4">--}}
                    {{--                        <a class="action-link dropdown-toggle" id="communitiesDropdown"--}}
                    {{--                           href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--                            <i class="fas fa-plus me-2"></i>Add To Community--}}
                    {{--                        </a>--}}
                    {{--                        <div class="dropdown-menu" aria-labelledby="communitiesDropdown">--}}
                    {{--                            @foreach(auth()->user()->communities as $community)--}}
                    {{--                                @if(!$dataset->isInCommunity($community->id))--}}
                    {{--                                    <a class="dropdown-item td-none" href="#">{{$community->name}}</a>--}}
                    {{--                                @endif--}}
                    {{--                            @endforeach--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                @endif--}}
                @endauth
            </div>
        @endslot

        @slot('body')
            @include('public.datasets.tabs.tabs')

            <br/>
            <x-card-container>
                @if (Request::routeIs('public.datasets.overview*'))
                    @include('public.datasets.tabs.overview-tab')
                @elseif (Request::routeIs('public.datasets.workflows*'))
                    @include('public.datasets.tabs.workflows-tab')
                @elseif (Request::routeIs('public.datasets.entities*'))
                    @include('public.datasets.tabs.entities-tab')
                    {{--            @elseif (Request::routeIs('public.datasets.activities*'))--}}
                    {{--                @include('public.datasets.tabs.activities-tab')--}}
                @elseif (Request::routeIs('public.datasets.files*'))
                    @include('public.datasets.tabs.files-tab')
                @elseif(Request::routeIs('public.datasets.folders*'))
                    @include('public.datasets.tabs.folders-tab')
                @elseif(Request::routeIs('public.datasets.communities.*'))
                    @include('public.datasets.tabs.communities')
                @elseif (Request::routeIs('public.datasets.comments*'))
                    @include('public.datasets.tabs.comments-tab')
                @endif
            </x-card-container>

        @endslot
    @endcomponent

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
