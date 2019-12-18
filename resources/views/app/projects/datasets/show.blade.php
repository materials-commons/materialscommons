@extends('layouts.app')

@section('pageTitle', 'Show Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.show', $project, $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
            <a class="float-right action-link"
               href="{{route('projects.datasets.edit', [$project, $dataset])}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="float-right action-link mr-4"
               href="{{route('projects.datasets.delete', [$project, $dataset])}}">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>

            @if (isset($dataset->published_at))
                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.unpublish', [$project, $dataset])}}">
                    <i class="fas fa-minus-circle mr-2"></i>Unpublish
                </a>
            @else
                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.publish', [$project, $dataset])}}">
                    <i class="fas fa-upload mr-2"></i>Publish
                </a>
            @endif
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
            @include('app.projects.datasets.tabs.tabs')
            <br>

            @if(Request::routeIs('projects.datasets.show'))
                @include('app.projects.datasets.tabs.files')
            @elseif(Request::routeIs('projects.datasets.show.next'))
                @include('app.projects.datasets.tabs.files')
            @elseif(Request::routeIs('projects.datasets.show.entities'))
                @include('app.projects.datasets.tabs.entities')
            @elseif(Request::routeIs('projects.datasets.show.workflows'))
                @include('app.projects.datasets.tabs.workflows')
            @elseif(Request::routeIs('projects.datasets.show.communities'))
                @include('app.projects.datasets.tabs.communities')
            @endif
        @endslot
    @endcomponent
@stop
