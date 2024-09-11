@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')

    <x-card>
        <x-slot:header>
            Data Explorer
            <x-datahq.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            <div class="row">
                <div class="offset-2 col-8">
                    <div class="card bg-light" style="border-color: #b3c2d9">
                        <div class="card-body">
                            <h5 class="card-title"><strong>Data Explorer</strong></h5>
                            <hr/>
                            <ul>
                                <li>Should this page start with an overview of the data?</li>
                                <li>Or... Should it start at a default, such as View: Samples, Select Data For:
                                    Project?
                                </li>
                                <li>Or should we show help here?</li>
                            </ul>
                            <p class="card-text">
                                The data explorer helps you to understand your data. Start by selecting
                                the type of data you want to look at in the "Get" dropdown. You will be
                                able to choose "Samples", "Computations" or "Processes". Then explore
                                the related data. You can build our a query to look at the data
                                in different ways, and explore the data in both table and chart formats.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot:body>
    </x-card>
@stop
