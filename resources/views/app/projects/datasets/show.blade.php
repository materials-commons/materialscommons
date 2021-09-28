@extends('layouts.app')

@section('pageTitle', 'Show Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.show.overview', $project, $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
            <a class="float-right action-link"
               href="{{$editRoute}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            @if(is_null($dataset->published_at))
                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.delete', [$project, $dataset])}}">
                    <i class="fas fa-trash-alt mr-2"></i>Delete
                </a>
            @endif

            @if (isset($dataset->published_at))
                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.refresh', [$project, $dataset])}}">
                    <i class="fas fa-sync mr-2"></i> Refresh
                </a>

                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.unpublish', [$project, $dataset])}}">
                    <i class="fas fa-minus-circle mr-2"></i>Unpublish
                </a>
            @elseif($dataset->hasSelectedFiles())
                @if(!isset($dataset->cleanup_started_at))
                    <a class="float-right action-link mr-4"
                       href="{{route('projects.datasets.publish', [$project, $dataset])}}" disabled=true>
                        <i class="fas fa-file-export mr-2"></i>Publish
                    </a>
                @endif
            @endif

            <div class="dropdown float-right mr-4">
                <a class="action-link dropdown-toggle" href="#" id="projectsDropdown" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-import mr-2"></i>Import Into Project
                </a>
                <div class="dropdown-menu" aria-labelledby="projectsDropdown">
                    @foreach(auth()->user()->projects as $p)
                        @if($p->owner_id == auth()->id() && $p->id != $dataset->project_id)
                            <a class="dropdown-item td-none"
                               href="{{route('projects.datasets.import-into-project', [$p, $dataset])}}">
                                {{$p->name}}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endslot

        @slot('body')
            @if(isset($dataset->cleanup_started_at))
                <div class="msg msg-danger">
                    Dataset cannot be published - It is still in the process of being unpublished
                    (Unpublish started {{$dataset->cleanup_started_at->diffForHumans()}}).
                </div>
            @endif
            @include('app.projects.datasets._dataset-status', [
                'defaultRoute' => route('projects.datasets.show.overview', [$project, $dataset]),
                'filesRoute' => route('projects.datasets.show.files', [$project, $dataset]),
                'workflowsRoute' => route('projects.datasets.show.workflows', [$project, $dataset]),
                'samplesRoute' => route('projects.datasets.show.entities', [$project, $dataset]),
            ])
            <br>
            @include('app.projects.datasets._show-tabs')
        @endslot
    @endcomponent
@stop
