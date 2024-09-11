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
            <div class="col-lg-10 float-right">
                <label>Select Data For:</label>
                <select name="what" class="selectpicker" id="select-data-for"
                        data-style="btn-light no-tt">
                    <option value="p:{{$project->id}}:de:state">Project</option>
                    @foreach($experiments as $experiment)
                        <option value="exp:{{$experiment->id}}:de:state">Experiment: {{$experiment->name}}</option>
                    @endforeach
                    <option value="ds:DS1:de:state">Dataset DS1</option>
                    <option value="ds:DS2:de:state">Dataset DS2</option>
                </select>
                <label for="select" class="ml-4">Show:</label>
                <select name="what" class="selectpicker" title="View" id="view_data"
                        data-style="btn-light no-tt">
                    <option value="samples" selected>Samples</option>
                    <option value="computations">Computations</option>
                    <option value="processes">Processes</option>
                </select>

            </div>
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

    @push('scripts')
        <script>
            $('#view_data').on('change', function () {
                let r = "";
                let selected = $(this).val();
                switch (selected) {
                    case 'samples':
                        r = "{{route('projects.datahq.sampleshq.index', [$project])}}";
                        break;
                    case 'computations':
                        r = "{{route('projects.datahq.computationshq.index', [$project])}}";
                        break;
                    case 'processes':
                        r = "{{route('projects.datahq.processeshq.index', [$project])}}";
                        break;
                }

                if (r !== "") {
                    window.location.href = r;
                }
            });

            const selectDataForRoute = "{{route('projects.datahq.save-data-for', [$project])}}";
            $('#select-data-for').on('change', function () {
                let selected = $(this).val();
                let formData = new FormData();
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                axios.post(selectDataForRoute, formData, config);
            });
        </script>
    @endpush

@stop
