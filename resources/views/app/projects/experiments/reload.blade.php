@extends('layouts.app')

@section('pageTitle', "{$project->name} - Reload Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')
    <h3 class="text-center">Reload Study: {{$experiment->name}}</h3>
    <br/>

    @if($publishedDatasets->isEmpty())
        <form method="post"
              action="{{route('projects.experiments.reload', [$project, $experiment])}}"
              id="experiment-reload">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-6" style="border-right: 2px solid black">
                    <h4>Using Google Sheet</h4>
                    @include('app.projects.experiments.partials._new-google-sheet')
                    @if($sheets->count() !== 0)
                        @include('app.projects.experiments.partials._existing-google-sheet')
                    @endif
                </div>
                <div class="col-6">
                    <h4><b>OR</b> Using Existing Excel File</h4>
                    @include('app.projects.experiments.partials._existing-excel-file')
                </div>
                <br>
                <div class="row">
                    <div class="col-10">
                        <br/>
                        <p>
                            <b>If loading from a Google Sheet, you must set the share permissions to "Anyone
                                with the link" under General Access in the share popup.</b>
                        </p>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="text-center">
                                        <img src="{{asset('images/google-sheets-share.png')}}"
                                             class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="float-end">
                <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                   class="action-link danger me-3">
                    Cancel
                </a>

                <a class="action-link me-3" href="#"
                   onclick="document.getElementById('experiment-reload').submit()">
                    Reload
                </a>
            </div>
        </form>

        @if($unpublishedDatasets->isNotEmpty())
            <br>
            <br>
            <p>
                The following unpublished datasets share samples with this study. If you choose to reload
                the study then the shared samples will need to be manually added back to the datasets.
            </p>
            <h5>Affected Unpublished Datasets</h5>
            <ul>
                @foreach($unpublishedDatasets as $ds)
                    <li>
                        <a href="{{route('projects.datasets.show', [$project, $ds])}}">{{$ds->name}}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    @else
        <br>
        <p>
            This study cannot be reloaded. It contains published datasets that would be affected
            by the reload.
        </p>
        <h5>Affected Published Datasets</h5>
        <ul>
            @foreach($publishedDatasets as $ds)
                <li>
                    <a href="{{route('projects.datasets.show', [$project, $ds])}}">{{$ds->name}}</a>
                </li>
            @endforeach
        </ul>
    @endif
@stop
