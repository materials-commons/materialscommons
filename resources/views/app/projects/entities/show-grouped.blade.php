@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*'))
    @php
        $title = "Compare Samples";
        $groupRoute = 'projects.entities.show-spread';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))
@else
    @php
        $title = "Compare Computations";
        $groupRoute = 'projects.computations.entities.show-spread';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.show', $project, $entity))
@endif

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col col-lg-4 float-right">
                <select class="selectpicker col-lg-10" data-live-search="true" title="{{$title}}">
                    @foreach($project->entities as $entry)
                        @if ($entry->name != $entity->name)
                            <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @component('components.card')
        @slot('header')
            Sample: {{$entity->name}}

            <a class="float-right action-link" href="#"
               onclick="window.location.replace('{{route($groupRoute, [$project, $entity])}}')">
                <i class="fas fa-object-ungroup mr-2"></i>Ungroup Processes
            </a>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$entity"/>

            <div class="row ml-1">
                @foreach($activityTypes as $activityType)
                    <div class="col-lg-5 col-md-10 col-sm-10 ml-2 mt-2 rounded border-blue border">
                        @include('partials.activities.activity-type-card', [
                            'activityType' => $activityType,
                            'files' => $filesByActivityType->get($activityType->name, []),
                            'attributes' => $attributesByActivityType->get($activityType->name, []),
                            'measurements' => $measurementsByActivityType->get($activityType->name, []),
                        ])
                    </div>
                @endforeach
            </div>
        @endslot
    @endcomponent
@endsection

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
