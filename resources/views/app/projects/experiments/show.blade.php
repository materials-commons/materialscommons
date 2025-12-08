@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))

@section('content')
    {{--    <div class="container">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col"></div>--}}
    {{--            <div class="col col-lg-4 float-end">--}}
    {{--                <select id="switch-experiments" class="selectpicker col-lg-10" data-live-search="true"--}}
    {{--                        title="Switch To Experiment">--}}
    {{--                    @foreach($project->experiments as $entry)--}}
    {{--                        @if ($entry->name != $experiment->name)--}}
    {{--                            <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>--}}
    {{--                        @endif--}}
    {{--                    @endforeach--}}
    {{--                </select>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    @if(!is_null($experiment->loaded_file_path) || !is_null($experiment->sheet))
        <a data-bs-toggle="modal" href="#reload-experiment-modal"
           class="action-link float-end">
            <i class="fas fa-sync-alt me-2"></i> Reload Study
        </a>
    @else
        <a class="action-link float-end"
           href="{{route('projects.experiments.show-reload', [$project, $experiment])}}">
            <i class="fas fa-sync-alt me-2 ms-3"></i>Reload Study
        </a>
    @endif
    <br/>
    <br/>
    <div class="col col-lg-4 float-end">
        <select id="switch-experiments" class="form-select" title="Switch To Study">
            <option value="">Switch To Study</option>
            @foreach($project->experiments as $entry)
                @if ($entry->name != $experiment->name)
                    <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <br/>
    @include('app.projects.experiments.tabs.experiment-tabs')

    @if (Request::routeIs('projects.experiments.show*'))
        @include('app.projects.experiments.tabs.overview-tab')
    @elseif(Request::routeIs('projects.experiments.entities'))
        @include('app.projects.experiments.tabs.entities-tab')
    @elseif(Request::routeIs('projects.experiments.computations.entities'))
        @include('app.projects.experiments.tabs.entities-tab')
    @elseif(Request::routeIs('projects.experiments.data-dictionary.activities'))
        @include('app.projects.experiments.tabs.activities-dd-tab')
    @elseif(Request::routeIs('projects.experiments.data-dictionary.entities'))
        @include('app.projects.experiments.tabs.entities-dd-tab')
    @elseif (Request::routeIs('projects.experiments.workflow'))
        @include('app.projects.experiments.tabs.workflows-tab')
        {{--            @elseif (Request::routeIs('projects.experiments.activities-tab'))--}}
        {{--                @include('app.projects.experiments.tabs.activities-tab')--}}
    @elseif (Request::routeIs('projects.experiments.etl_runs'))
        @include('app.projects.experiments.tabs.etl-runs-tab')
    @endif

    @include('app.projects.experiments.modals._reload-experiment-modal')

    @push('scripts')
        <script>
            $(document).ready(() => {
                new TomSelect("#switch-experiments", {
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    onChange: function (selected) {
                        if (selected) {
                            window.location.href = route('projects.experiments.show', {
                                project: "{{$project->id}}",
                                experiment: selected
                            });
                        }
                    }
                });
            });
        </script>
    @endpush
@stop
