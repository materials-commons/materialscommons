@extends('layouts.app')

@section('pageTitle', "{$project->name} - DataHQ")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')

    <x-card>
        <x-slot:header>
            DataHQ
            <div class="col-lg-8 float-right">
                <label>Select Data For:</label>
                <select name="what" class="selectpicker"
                        data-style="btn-light no-tt">
                    <option value="proj">Project</option>
                    @foreach($experiments as $experiment)
                        <option value="exp:{{$experiment->id}}">Experiment: {{$experiment->name}}</option>
                    @endforeach
                </select>
                <select name="what" class="selectpicker" title="Load Query"
                        data-style="btn-light no-tt">
                    <option value="proj">Annealed Samples</option>
                    <option value="proj">Stress vs Strain</option>
                </select>
            </div>
        </x-slot:header>

        <x-slot:body>
            <form>
                <div class="form-row">
                    <div class="form-group col-8">
                        <label for="mql">Query</label>
                        <textarea class="form-control col-12" id="mql" placeholder="Query..."
                                  rows="{{line_count($query, 2)+1}}">{{$query}}</textarea>

                    </div>
                    <div class="form-group col-4">
                        <br>
                        <br>
                        <a class="btn btn-danger" href="#">Reset</a>
                        <a class="btn btn-warning" href="#">Save</a>
                        <a class="btn btn-success" href="#">Run</a>
                    </div>
                </div>
            </form>
            @include('app.projects.datahq.tabs.tabs')
            <div class="mt-2">
                @if(Request::routeIs('projects.datahq.index'))
                    @include('app.projects.datahq.tabs.processes')
                @elseif(Request::routeIs('projects.datahq.entities'))
                    @include('app.projects.datahq.tabs.entities')
                @elseif(Request::routeIs('projects.datahq.results'))
                    @include('app.projects.datahq.tabs.results')
                @endif
            </div>
        </x-slot:body>
    </x-card>

@stop