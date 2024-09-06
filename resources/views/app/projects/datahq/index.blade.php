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
            <div class="col-lg-8 float-right">
                <label>Select Data For:</label>
                <select name="what" class="selectpicker"
                        data-style="btn-light no-tt">
                    <option value="proj">Project</option>
                    @foreach($experiments as $experiment)
                        <option value="exp:{{$experiment->id}}">Experiment: {{$experiment->name}}</option>
                    @endforeach
                    <option value="ds:DS1">Dataset DS1</option>
                    <option value="ds:DS2">Dataset DS2</option>
                </select>
                <select name="what" class="selectpicker" title="Load Saved Query"
                        data-style="btn-light no-tt">
                    <option value="proj">Annealed Samples</option>
                    <option value="proj">Stress vs Strain</option>
                </select>
                <a class="btn btn-danger ml-4" href="#">Reset</a>
                <a class="btn btn-warning" href="#">Save</a>
                <a class="btn btn-success" href="#">Run</a>
            </div>
        </x-slot:header>

        <x-slot:body>
            <div class="row">
                <div class="col-6">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="select">Show</label>
                                <input type="text" class="col-12" value="{{$query}}" placeholder="what to show...">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="mql">When</label>
                                <textarea class="form-control col-12" id="mql" placeholder="what to match on..."
                                          rows="{{line_count($query, 2)+1}}"></textarea>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <form>
                        <label>Overview</label>
                        <p>
                            This area to be used for an overview of the data shown on the page. For example:
                        </p>
                        @if(Request::routeIs('projects.datahq.entities'))
                            <p>
                                These attributes are from 2 samples (
                                <a href="#">S1 (experiment E1)</a>
                                <a href="#">S1 (experiment E2)</a>).
                                These samples are used in 6 process (<a href="#">Aging Analysis</a>,
                                <a href="#">Aging HT</a>
                                <a href="#">Cogging</a>
                                <a href="#">Extrusion</a>
                                <a href="#">Hardness Test</a>
                                <a href="#">Solution HT</a>).

                            </p>
                        @elseif(Request::routeIs('projects.datahq.index'))
                            <p>
                                There are 2 samples (<a href="#">S1 (experiment E1)</a>
                                <a href="#">S1 (experiment E2)</a>) used in these samples.
                                There are 13 process attributes used in these processes.
                            </p>
                        @elseif(Request::routeIs('projects.datahq.results'))
                            <p>
                                You have selected 2 attributes (<a href="#">stress</a>, <a href="#">strain</a>).
                            </p>
                        @endif
                        <p>Other stuff?? What format/layout?</p>

                    </form>
                </div>
            </div>
            <label>Show:</label>
            <select id="dhq_page" name="what" class="selectpicker"
                    data-style="btn-light no-tt">
                <option value="samples">Samples</option>
                <option value="computations">Computations</option>
                <option value="processes" @if(Request::routeIs('projects.datahq.index')) selected @endif>Processes
                </option>
                <option value="activities">Activities</option>
                <option value="sample_attributes" @if(Request::routeIs('projects.datahq.entities')) selected @endif>
                    Sample Attributes
                </option>
                <option value="process_attributes">Process Attributes</option>
                <option value="computation_attributes">Computation Attributes</option>
                <option value="activity_attributes">Activity Attributes</option>
                <option value="results" @if(Request::routeIs('projects.datahq.results')) selected @endif>Results
                </option>
            </select>

            <hr>
            <div class="mt-2">
                @if(Request::routeIs('projects.datahq.index'))
                    @include('app.projects.datahq.pages.processes')
                @elseif(Request::routeIs('projects.datahq.entities'))
                    @include('app.projects.datahq.pages.entities')
                @elseif(Request::routeIs('projects.datahq.results'))
                    @include('app.projects.datahq.pages.results')
                @endif
            </div>
        </x-slot:body>
    </x-card>

    @push('scripts')
        <script>
            $('#dhq_page').on('change', function () {
                let r = "";
                let selected = $(this).val();
                switch (selected) {
                    case 'samples':
                        break;
                    case 'computations':
                        break;
                    case 'processes':
                        r = "{{route('projects.datahq.index', [$project])}}";
                        break;
                    case 'activities':
                        break;
                    case 'sample_attributes':
                        r = "{{route('projects.datahq.entities', [$project])}}";
                        break;
                    case 'process_attributes':
                        break;
                    case 'computation_attributes':
                        break;
                    case 'activity_attributes':
                        break;
                    case 'results':
                        r = "{{route('projects.datahq.results', [$project])}}";
                        break;
                }
                if (r !== "") {
                    window.location.href = r;
                }
            });
        </script>
    @endpush

@stop
