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

    <div class="row mb-3 ms-1">
        <div class="col-12">
            @if($entity->category == "computational")
                <h4>Computation: {{$entity->name}}</h4>
            @else
                <h4>Sample: {{$entity->name}}</h4>
            @endif

            <x-show-standard-details :item="$entity"/>
        </div>
    </div>

    <div class="row mb-3 ms-1">
        <div class="col-lg-5">
            <select id="entity-select" placeholder="{{$title}}" class="form-select">
                <option value="">{{$title}}</option>
                @foreach($allEntities as $entry)
                    @if ($entry->name != $entity->name)
                        <option value="{{$entry->id}}">{{$entry->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-5 d-flex justify-content-end align-items-center">
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
                    <a class="action-link me-3"
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
    </div>

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
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(() => {
            new TomSelect("#entity-select", {
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function (value) {
                    if (value) {
                        window.location.href = route('projects.entities.compare', {
                            project: "{{$project->id}}",
                            entity1: "{{$entity->id}}",
                            entity2: value
                        });
                    }
                }
            });
        });
    </script>
@endpush
