@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$dataset->name}}</dd>
                    <dt class="col-sm-2">License</dt>
                    <dd class="col-sm-10"><a href="{{$dataset->license_link}}">{{$dataset->license}}</a></dd>
                    <dt class="col-sm-2">Authors</dt>
                    <dd class="col-sm-10">{{$dataset->authors}}</dd>
                    <dt class="col-sm-2">Institution</dt>
                    <dd class="col-sm-10">{{$dataset->institution}}</dd>
                    <dt class="col-sm-2">Funding</dt>
                    <dd class="col-sm-10">{{$dataset->funding}}</dd>
                    <dt class="col-sm-2">Published</dt>
                    <dd class="col-sm-10">{{$dataset->published_at->diffForHumans()}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$dataset->description}}</p>
            </div>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{setActiveNavByName('projects.experiments.show')}} active" href="#">
                        Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{setActiveNavByName('projects.experiments.workflow')}}" href="#">
                        Workflow
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{setActiveNavByName('projects.experiments.samples')}}" href="#">
                        Samples
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{setActiveNavByName('projects.experiments.processes')}}" href="#">
                        Processes
                    </a>
                </li>
            </ul>

            <br>
        @endslot
    @endcomponent
@stop
