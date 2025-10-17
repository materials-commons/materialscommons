@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Computation")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.activities.computations.show', $project, $activity))

@section('content')
    @component('components.card')
        @slot('header')
            Computation {{$activity->atype}}
        @endslot

        @slot('body')
            <div class="mt-2">
                <h5>
                    <span>{{$activity->name}}</span>
                </h5>
                @if(!blank($activity->description))
                    <form>
                        <div class="mb-3">
                            <textarea class="form-control" readonly>{{$activity->description}}</textarea>
                        </div>
                    </form>
                @endisset
                @include('partials.activities._activity-attributes', ['activity' => $activity])
                <h6>Measurements</h6>
                @include('partials.activities._activity-measurements', ['activity' => $activity])
                <h6>Files</h6>
                @include('partials.activities._activity-files', ['activity' => $activity])
            </div>
        @endslot
    @endcomponent
@endsection
