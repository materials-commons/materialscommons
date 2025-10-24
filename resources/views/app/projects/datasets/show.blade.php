@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.show.overview', $project, $dataset))

@section('content')

    Dataset: {{$dataset->name}}
    <a class="float-end action-link"
       href="{{$editRoute}}">
        <i class="fas fa-edit me-2"></i>Edit
    </a>

    @if(is_null($dataset->published_at))
        <a class="float-end action-link me-4"
           href="{{route('projects.datasets.delete', [$project, $dataset])}}">
            <i class="fas fa-trash-alt me-2"></i>Delete
        </a>
    @endif

    @if (isset($dataset->published_at))
        <a class="float-end action-link me-4"
           href="{{route('projects.datasets.refresh', [$project, $dataset])}}">
            <i class="fas fa-sync me-2"></i> Refresh
        </a>

        @if(is_null($dataset->publish_started_at))
            <a class="float-end action-link me-4"
               href="{{route('projects.datasets.unpublish', [$project, $dataset])}}">
                <i class="fas fa-minus-circle me-2"></i>Unpublish
            </a>
        @endif
    @elseif($dataset->hasSelectedFiles())
        @if(!isset($dataset->cleanup_started_at))
            <a class="float-end action-link me-4"
               href="{{route('projects.datasets.publish', [$project, $dataset])}}" disabled=true>
                <i class="fas fa-file-export me-2"></i>Publish
            </a>
        @endif
    @endif

    <div class="dropdown float-end me-4">
        <a class="action-link dropdown-toggle" href="#" id="projectsDropdown" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-import me-2"></i>Import Into Project
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

    @if(isset($dataset->cleanup_started_at))
        <div class="msg msg-danger">
            Dataset cannot be published - It is still in the process of being unpublished
            (Unpublish started {{$dataset->cleanup_started_at->diffForHumans()}}).
        </div>
    @elseif(isset($dataset->publish_started_at))
        <div class="msg msg-danger">
            Dataset is being published. It cannot be unpublished while this happening.
            (Publish started {{$dataset->publish_started_at->diffForHumans()}}).
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
@stop
