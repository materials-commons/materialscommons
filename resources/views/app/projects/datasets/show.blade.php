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
               href="{{route('projects.datasets.edit', [$project, $dataset])}}">
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
                <a class="float-right action-link mr-4"
                   href="{{route('projects.datasets.publish', [$project, $dataset])}}">
                    <i class="fas fa-file-export mr-2"></i>Publish
                </a>
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
            <ul class="steps">
                @if(blank($dataset->description))
                    <li class="step step-error">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"></i></span>
                            <span class="step-text">Overview</span>
                        </div>
                    </li>
                @else
                    <li class="step step-success">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                            <span class="step-text">Overview</span>
                        </div>
                    </li>
                @endif

                @if($dataset->hasSelectedFiles())
                    <li class="step step-success">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                            <span class="step-text">Files</span>
                        </div>
                    </li>
                @else
                    <li class="step step-error">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"></i></span>
                            <span class="step-text">Files</span>
                        </div>
                    </li>
                @endif

                @if($entities->count() != 0)
                    <li class="step step-success">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                            <span class="step-text">Samples (Optional)</span>
                        </div>
                    </li>
                @else
                    <li class="step">
                        <div class="step-content">
                            <span class="step-circle">3</span>
                            <span class="step-text">Samples (Optional)</span>
                        </div>
                    </li>
                @endif

                @if($dataset->workflows->count() != 0)
                    <li class="step step-success">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                            <span class="step-text">Workflow (Optional)</span>
                        </div>
                    </li>
                @else
                    <li class="step">
                        <div class="step-content">
                            <span class="step-circle">4</span>
                            <span class="step-text">Workflow (Optional)</span>
                        </div>
                    </li>
                @endif

                @if(is_null($dataset->published_at))
                    <li class="step">
                        <div class="step-content">
                            <span class="step-circle">5</span>
                            <span class="step-text">Published</span>
                        </div>
                    </li>
                @else
                    <li class="step step-success">
                        <div class="step-content">
                            <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                            <span class="step-text">Published</span>
                        </div>
                    </li>
                @endif
            </ul>
            <br>
            @include('app.projects.datasets._show-tabs')
        @endslot
    @endcomponent
@stop
