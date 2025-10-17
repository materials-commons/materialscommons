@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*') || Request::routeIs('projects.experiments.entities*'))
    @php
        $title = "Compare Samples";
        $groupRoute = 'projects.entities.show';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))
@else
    @php
        $title = "Compare Computations";
        $groupRoute = 'projects.computations.entities.show';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.show', $project, $entity))
@endif

@section('content')
    @component('components.card')
        @slot('header')
            @if($entity->category == "computational")
                Computation: {{$entity->name}}
            @else
                Sample: {{$entity->name}}
            @endif

            <div class="float-right">

                @if(isset($prevEntity))
                    @if($entity->category == "computational")
                        <a class="action-link me-3"
                           href="{{route('projects.computations.entities.show-spread', [$project, $prevEntity])}}">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </a>
                    @else
                        <a class="action-link me-3"
                           href="{{route('projects.entities.show-spread', [$project, $prevEntity])}}">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </a>
                    @endif
                @endif

                @if(isset($nextEntity))
                    @if($entity->category == "computational")
                        <a class="action-link me-3"
                           href="{{route('projects.computations.entities.show-spread', [$project, $nextEntity])}}">
                            Next<i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    @else
                        <a class="action-link me-5"
                           href="{{route('projects.entities.show-spread', [$project, $nextEntity])}}">
                            Next<i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    @endif
                @endif

                <a class="action-link" href="#"
                   onclick="window.location.replace('{{route($groupRoute, [$project, $entity])}}')">
                    <i class="fas fa-object-group me-2"></i>Group By Process Type
                </a>
            </div>

            <div class="col col-lg-4 float-right">
                <select class="selectpicker col-lg-10 mc-select"
                        data-style="btn-light no-tt"
                        data-live-search="true" title="{{$title}}">
                    @foreach($allEntities as $entry)
                        @if ($entry->name != $entity->name)
                            <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$entity"/>

            <div class="row ms-1">
                @foreach($activities as $activity)
                    <div class="col-lg-5 col-md-10 col-sm-10 ms-2 mt-2 mb-2 white-box">
                        <x-activities.activities-card :activity="$activity"
                                                      :project="$project"
                                                      :experiment="$experiment"
                                                      :user="$user"/>
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(() => {
            $('select').on('change', () => {
                let selected = $('.selectpicker option:selected').val();
                window.location.href = route('projects.entities.compare', {
                    project: "{{$project->id}}",
                    entity1: "{{$entity->id}}",
                    entity2: selected
                });
            });
        });
    </script>
@endpush
