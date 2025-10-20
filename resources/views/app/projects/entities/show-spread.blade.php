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

            <div class="float-end">

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

            <div class="col-lg-4 float-end me-4">
                <select id="entity-select" placeholder="{{$title}}" class="form-select">
                    <option value="">{{$title}}</option>
                    @foreach($allEntities as $entry)
                        @if ($entry->name != $entity->name)
                            <option value="{{$entry->id}}">{{$entry->name}}</option>
                        @endif
                    @endforeach
                </select>



{{--                <select id="select-beast" placeholder="Select a person..." autocomplete="off">--}}
{{--                    <option value="">Select a person...</option>--}}
{{--                    <option value="4">Thomas Edison</option>--}}
{{--                    <option value="1">Nikola</option>--}}
{{--                    <option value="3">Nikola Tesla</option>--}}
{{--                    <option value="5">Arnold Schwarzenegger</option>--}}
{{--                </select>--}}


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
            console.log('TomSelect');


            new TomSelect("#entity-select",{
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function(value) {
                    if (value) {
                        console.log('value', value);
                        window.location.href = route('projects.entities.compare', {
                            project: "{{$project->id}}",
                            entity1: "{{$entity->id}}",
                            entity2: value
                        });
                    }
                }
            });


        {{--new TomSelect('#entity-select', {--}}
            {{--    create: true,--}}
            {{--    allowEmptyOption: true,--}}
            {{--    onChange: function(value) {--}}
            {{--        if (value) {--}}
            {{--            window.location.href = route('projects.entities.compare', {--}}
            {{--                project: "{{$project->id}}",--}}
            {{--                entity1: "{{$entity->id}}",--}}
            {{--                entity2: value--}}
            {{--            });--}}
            {{--        }--}}
            {{--    }--}}
            {{--});--}}
        });
    </script>
@endpush
