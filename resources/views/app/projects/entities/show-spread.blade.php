@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

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

            <a class="float-right action-link" href="#"
               onclick="window.location.replace('{{route('projects.entities.show', [$project, $entity])}}')">
                <i class="fas fa-object-group mr-2"></i>Group By Process Type
            </a>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$entity"/>

            <div class="row ml-1">
                @foreach($activities as $activity)
                    <div class="col-lg-5 col-md-10 col-sm-10 ml-2 mt-2 rounded border-blue border">
                        @include('partials.activities.activity-card', ['activity' => $activity])
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
