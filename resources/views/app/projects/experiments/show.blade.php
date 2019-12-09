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
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$experiment->name}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$experiment->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$experiment->updated_at->diffForHumans()}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$experiment->description}}</p>
            </div>

            <br>

            @include('app.projects.experiments.tabs.experiment-tabs')

            <br>

            @if (Request::routeIs('projects.experiments.show*'))
                @include('app.projects.experiments.tabs.workflows-tab')
            @elseif (Request::routeIs('projects.experiments.entities-tab'))
                @include('app.projects.experiments.tabs.entities-tab')
            @elseif (Request::routeIs('projects.experiments.activities-tab'))
                @include('app.projects.experiments.tabs.activities-tab')
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
