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
                @foreach($activities as $activity)
                    <div class="col-5 @if($loop->iteration % 2 == 0) ml-2 @endif bg-grey-9 mt-2">
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
                }).url();
            });
        });
    </script>
@endpush