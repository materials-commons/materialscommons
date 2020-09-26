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
            @auth
                @if($dataset->canEdit())
                    <a class="action-link float-right mr-4"
                       href="{{route('projects.datasets.edit', [$dataset->project_id, $dataset->id, 'public' => true])}}">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif

                <div class="dropdown float-right mr-4">
                    <a class="action-link dropdown-toggle" href="#" id="projectsDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-import mr-2"></i>Import Into Project
                    </a>
                    <div class="dropdown-menu" aria-labelledby="projectsDropdown">
                        @foreach(auth()->user()->projects as $project)
                            @if($project->owner_id == auth()->id() && $project->id != $dataset->project_id)
                                <a class="dropdown-item td-none"
                                   href="{{route('public.datasets.import-into-project', [$dataset, $project])}}">
                                    {{$project->name}}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>


                @if(auth()->user()->hasCommunities())
                    <div class="dropdown float-right mr-4">
                        <a class="action-link dropdown-toggle" id="communitiesDropdown"
                           href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus mr-2"></i>Add To Community
                        </a>
                        <div class="dropdown-menu" aria-labelledby="communitiesDropdown">
                            @foreach(auth()->user()->communities as $community)
                                @if(!$dataset->isInCommunity($community->id))
                                    <a class="dropdown-item td-none" href="#">{{$community->name}}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth
        @endslot

        @slot('body')

            @include('public.datasets.tabs.tabs')

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
            @elseif(Request::routeIs('public.datasets.communities.*'))
                @include('public.datasets.tabs.communities')
            @elseif (Request::routeIs('public.datasets.comments*'))
                @include('public.datasets.tabs.comments-tab')
            @endif

        @endslot
    @endcomponent

    @isset($dsAnnotation)
        @push('googleds')
            <script type="application/ld+json">
                @json($dsAnnotation, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endpush
    @endisset
@stop
