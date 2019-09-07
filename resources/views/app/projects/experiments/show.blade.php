@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop

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

            @component('components.experiment-tabs', ['project' => $project, 'experiment' => $experiment])
            @endcomponent

            <div class="ml-2">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$experiment->name}}</dd>
                    <dt class="col-sm-2">Description</dt>
                    <dd class="col-sm-10">{{$experiment->description}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$experiment->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$experiment->updated_at->diffForHumans()}}</dd>
                </dl>
            </div>
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
