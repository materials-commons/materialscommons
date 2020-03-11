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
            @include('app.projects.datasets._show')
        @endslot
    @endcomponent
@stop
