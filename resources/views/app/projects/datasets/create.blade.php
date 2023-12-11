@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset
        @endslot

        @slot('body')
            <ul class="steps">
                <li class="step step-active">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                        <span class="step-text">Overview</span>
                    </div>
                </li>

                <li class="step">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                        <span class="step-text">Files</span>
                    </div>
                </li>

                <li class="step">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                        <span class="step-text">Samples (Optional)</span>
                    </div>
                </li>

                <li class="step">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                        <span class="step-text">Workflow (Optional)</span>
                    </div>
                </li>

                <li class="step">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                        <span class="step-text">Published</span>
                    </div>
                </li>
            </ul>
            <br>
            @include('app.projects.datasets._create')
        @endslot
    @endcomponent
@stop
