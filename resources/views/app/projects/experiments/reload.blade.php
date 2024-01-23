@extends('layouts.app')

@section('pageTitle', "{$project->name} - Reload Experiment")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Reload Experiment: {{$experiment->name}}
        @endslot

        @slot('body')
            @if($publishedDatasets->isEmpty())
                <form method="post"
                      action="{{route('projects.experiments.reload', [$project, $experiment])}}"
                      id="experiment-reload">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <p>
                            <b>If loading from a Google Sheet, you must set the share permissions to "Anyone with the
                                link"
                                under General Access in the share popup.</b>
                        </p>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="text-center">
                                        <img src="{{asset('images/google-sheets-share.png')}}" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <label for="url-id">Reload Experiment From Google Sheet</label>
                        <input class="form-control" name="sheet_url" type="url" placeholder="Google Sheet URL.."
                               id="url-id">
                        <span class="htmx-indicator"><i class="fas fa-spinner fa-spin"></i></span>
                        <div id="google-sheet-title"></div>
                    </div>
                    @if($sheets->count() !== 0)
                        <span><b>OR</b></span>
                        <br>
                        <label for="sheet_id">Select existing Google Spreadsheet</label>
                        <select name="sheet_id" class="selectpicker col-lg-10" data-live-search="true"
                                title="Select Google Sheet">
                            <option value=""></option>
                            @if(!is_null($sheet))
                                @foreach($sheets as $s)
                                    <option data-tokens="{{$s->id}}" value="{{$s->id}}"
                                            @selected($s->id == $sheet->id)>{{$s->title}}</option>
                                @endforeach
                            @else
                                @foreach($sheets as $s)
                                    <option data-tokens="{{$s->id}}" value="{{$s->id}}">{{$s->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    @endif
                    <span><b>OR</b></span>
                    <br>
                    <label for="file_id">Reload Experiment From</label>
                    <select name="file_id" class="selectpicker col-lg-10" data-live-search="true"
                            title="Select Spreadsheet">
                        <option value=""></option>
                        @if(!is_null($file))
                            @foreach($excelFiles as $f)
                                @if ($f->directory->path === "/")
                                    <option data-tokens="{{$f->id}}"
                                            value="{{$f->id}}" @selected($f->id == $file->id)>/{{$f->name}}</option>
                                @else
                                    <option data-tokens="{{$f->id}}" value="{{$f->id}}" @selected($f->id == $file->id)>
                                        {{$f->directory->path}}/{{$f->name}}</option>
                                @endif
                            @endforeach
                        @else
                            @foreach($excelFiles as $f)
                                @if ($f->directory->path === "/")
                                    <option data-tokens="{{$f->id}}" value="{{$f->id}}">/{{$f->name}}</option>
                                @else
                                    <option data-tokens="{{$f->id}}" value="{{$f->id}}">
                                        {{$f->directory->path}}/{{$f->name}}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    <div class="float-right">
                        <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                           class="action-link danger mr-3">
                            Cancel
                        </a>

                        <a class="action-link mr-3" href="#"
                           onclick="document.getElementById('experiment-reload').submit()">
                            Reload
                        </a>
                    </div>
                </form>

                @if($unpublishedDatasets->isNotEmpty())
                    <br>
                    <br>
                    <p>
                        The following unpublished datasets share samples with this experiment. If you choose to reload
                        the experiment then the shared samples will need to manually be added back to the datasets.
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
                    This experiment cannot be reloaded. It contains published datasets that would be affected
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
        @endslot
    @endcomponent
@stop
