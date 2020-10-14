@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

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
                <li class="step step-success">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                        <span class="step-text">Add Overview</span>
                    </div>
                </li>
                <li class="step step-success">
                    <div class="step-content">
                        <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                        <span class="step-text">Add Files</span>
                    </div>
                </li>
                <li class="step step-success">
                    <a class="step-content" href="#">
                        <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                        <span class="step-text">Add Samples <br>
                            <span class="ml-2">(Optional)</span>
                        </span>
                    </a>
                </li>
                <li class="step">
                    <div class="step-content">
                        <span class="step-circle">4</span>
                        <span class="step-text">Add Workflow <br>
                            <span class="ml-3">(Optional)</span>
                        </span>
                    </div>
                </li>
                <li class="step step-success">
                    <a class="step-content" href="#">
                        <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                        <span class="step-text">Published</span>
                    </a>
                </li>
            </ul>
            @include('app.projects.datasets._create')
        @endslot
    @endcomponent
@stop
