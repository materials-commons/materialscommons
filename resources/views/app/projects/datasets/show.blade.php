@extends('layouts.app')

@section('pageTitle', 'Show Dataset')

@section('nav')
    @include('layouts.navs.project')
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
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$dataset->name}}</dd>
                    <dt class="col-sm-2">Experiments</dt>
                    <dd class="col-sm-10">
                        @foreach($dataset->experiments as $experiment)
                            <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                               class="mr-2">
                                {{$experiment->name}}
                            </a>
                        @endforeach
                    </dd>
                    <dt class="col-sm-2">Published</dt>
                    <dd class="col-sm-10">
                        @if (isset($dataset->published_at))
                            {{$dataset->published_at->diffForHumans()}}
                        @else
                            Not Published
                        @endif
                    </dd>
                    <dt class="col-sm-2">DOI</dt>
                    <dd class="col-sm-10">{{$dataset->doi}}</dd>
                    <dt class="col-sm-2">Funding</dt>
                    <dd class="col-sm-10">{{$dataset->funding}}</dd>
                    <dt class="col-sm-2">License</dt>
                    <dd class="col-sm-10">{{$dataset->license}}</dd>
                    <dt class="col-sm-2">Authors</dt>
                    <dd class="col-sm-10">{{$dataset->authors}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$dataset->description}}</p>
            </div>
            <br>
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
    @endif

@stop