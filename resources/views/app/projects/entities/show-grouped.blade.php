@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*'))
    @php
        $title = "Compare Samples";
        $spreadRoute = 'projects.entities.show-spread';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))
@else
    @php
        $title = "Compare Computations";
        $spreadRoute = 'projects.computations.entities.show-spread';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.show', $project, $entity))
@endif

@section('content')

    <div class="row mb-3">
        <div class="col-12">
            @if($entity->category == "computational")
                <h4>Computation: {{$entity->name}}</h4>
            @else
                <h4>Sample: {{$entity->name}}</h4>
            @endif

            <x-show-standard-details :item="$entity"/>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-6">
            <select id="sg-entities" class="form-select" title="{{$title}}">
                @foreach($project->entities as $entry)
                    @if ($entry->name != $entity->name)
                        <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
{{--        <div class="col-lg-6 d-flex justify-content-end align-items-centers">--}}
{{--            <a class="float-end action-link" href="#"--}}
{{--               onclick="window.location.replace('{{route($groupRoute, [$project, $entity])}}')">--}}
{{--                <i class="fas fa-object-ungroup me-2"></i>Ungroup Processes--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>

    <div class="row g-3">
        @foreach($activityTypes as $activityType)
            <div class="col-lg-6 col-md-12">
                <div class="white-box h-100">
                    @include('partials.activities.activity-type-card', [
                        'activityType' => $activityType,
                        'files' => $filesByActivityType->get($activityType->name, []),
                        'attributes' => $attributesByActivityType->get($activityType->name, []),
                        'measurements' => $measurementsByActivityType->get($activityType->name, []),
                    ])
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(() => {
            new TomSelect("#sg-entities", {
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function (selected) {
                    if (selected) {
                        window.location.href = route('projects.entities.compare', {
                            project: "{{$project->id}}",
                            entity1: "{{$entity->id}}",
                            entity2: selected
                        });
                    }
                }
            });
        });
    </script>
@endpush
