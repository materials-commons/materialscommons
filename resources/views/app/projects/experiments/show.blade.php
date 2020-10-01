@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col col-lg-4 float-right">
                <select class="selectpicker col-lg-10" data-live-search="true" title="Switch To Experiment">
                    @foreach($project->experiments as $entry)
                        @if ($entry->name != $experiment->name)
                            <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @component('components.card')
        @slot('header')
            Experiment {{$experiment->name}}
            @if($excelFilesCount !== 0)
                <a class="action-link float-right"
                   href="{{route('projects.experiments.show-reload', [$project, $experiment])}}">
                    <i class="fas fa-sync-alt mr-2"></i>Reload Experiment
                </a>
            @endif
        @endslot

        @slot('body')
            @include('app.projects.experiments.tabs.experiment-tabs')

            @if (Request::routeIs('projects.experiments.show*'))
                @include('app.projects.experiments.tabs.overview-tab')
            @elseif(Request::routeIs('projects.experiments.entities'))
                @include('app.projects.experiments.tabs.entities-tab')
            @elseif(Request::routeIs('projects.experiments.data-dictionary.activities'))
                @include('app.projects.experiments.tabs.activities-dd-tab')
            @elseif(Request::routeIs('projects.experiments.data-dictionary.entities'))
                @include('app.projects.experiments.tabs.entities-dd-tab')
            @elseif (Request::routeIs('projects.experiments.workflow'))
                @include('app.projects.experiments.tabs.workflows-tab')
                {{--            @elseif (Request::routeIs('projects.experiments.activities-tab'))--}}
                {{--                @include('app.projects.experiments.tabs.activities-tab')--}}
            @endif

        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('select').on('change', () => {
                    let selected = $('.selectpicker option:selected').val();
                    window.location.href = route('projects.experiments.show', {
                        project: "{{$project->id}}",
                        experiment: selected
                    }).url();
                });
            });
        </script>
    @endpush
@stop
