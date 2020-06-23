@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col col-lg-4 float-right">
                <select class="selectpicker col-lg-10" data-live-search="true" title="Compare Samples">
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
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $entity])
            @endcomponent

            <div class="row ml-1">
                @foreach($activityTypes as $activityType)
                    <div class="col-lg-3 col-md-5 col-sm-5 ml-2 bg-grey-9 mt-2">
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
                }).url();
            });
        });
    </script>
@endpush