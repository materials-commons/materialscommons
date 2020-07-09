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
            @else
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
            @include('app.projects.datasets._show')
            <div class="float-right">
                <a class="action-link mr-3" href="{{route('projects.datasets.edit', [$project, $dataset])}}">Edit</a>
                <a class="action-link mr-3" href="{{route('projects.datasets.index', [$project])}}">Done</a>
                @if (isset($dataset->published_at))
                    <a class="action-link" href="{{route('projects.datasets.unpublish', [$project, $dataset])}}">
                        Done And Unpublish
                    </a>
                @else
                    <a class="action-link" href="{{route('projects.datasets.publish', [$project, $dataset])}}">
                        Done And Publish
                    </a>
                @endif
            </div>
            <br>
            @include('app.projects.datasets._show-tabs')
        @endslot
    @endcomponent
@stop
