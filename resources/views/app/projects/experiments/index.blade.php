@extends('layouts.app')

@section('pageTitle', "{$project->name} - Studies")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))

@section('content')

    @component('components.card')
        @slot('header')
            Studies for {{$project->name}}
            <a class="action-link float-end"
               href="{{route('projects.experiments.create', ['project' => $project->id])}}">
                <i class="fas fa-plus mr-2"></i>Create Study
            </a>
        @endslot
        @slot('body')
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="table-container">
                        <div class="card table-card">
                            <div class="card-body inner-card">
                                @include('app.projects.experiments._experiments-table')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('app.projects.experiments._experiment-info-card')
                </div>
            </div>
        @endslot
    @endcomponent
@stop
