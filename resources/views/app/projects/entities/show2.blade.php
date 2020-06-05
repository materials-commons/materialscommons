@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))

@section('content')
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