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
                <div class="col-8">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="mql">Filter:</label>
                                <textarea class="form-control col-12" id="mql" placeholder="Filter by..."
                                          rows="{{line_count($query, 2)+1}}"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4">
                    <div class="row col-12">
                        <a class="btn btn-danger" href="#">Reset</a>
                        <a class="btn btn-warning ml-2" href="#">Save</a>
                        <a class="btn btn-success ml-2" href="#">Run</a>
                    </div>

                    <div class="row col-12">
                        <select name="what" class="selectpicker mt-4" title="Load Saved Filter"
                                data-style="btn-light no-tt">
                            <option value="proj">Annealed Samples</option>
                            <option value="proj">Stress vs Strain</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="mt-2">
                <x-datahq.view-controls :project="$project"/>
                <br/>
                @if(Request::routeIs('projects.datahq.sampleshq.index'))
                    @include('app.projects.datahq.sampleshq.pages.samples')
                @elseif(Request::routeIs('projects.datahq.sampleshq.entity-attributes.filters'))
                    @include('app.projects.datahq.sampleshq.pages.entity-attribute-filters')
                @elseif(Request::routeIs('projects.datahq.sampleshq.activity-attributes.filters'))
                    @include('app.projects.datahq.sampleshq.pages.activity-attribute-filters')
                @elseif(Request::routeIs('projects.datahq.sampleshq.activities.filters'))
                    @include('app.projects.datahq.sampleshq.pages.activity-filters')
                @endif
            </div>
        </x-slot:body>
    </x-card>
@stop
