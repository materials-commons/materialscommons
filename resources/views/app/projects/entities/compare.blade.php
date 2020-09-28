@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Compare sample {{$entity1->name}} to {{$entity2->name}}
        @endslot

        @slot('body')
            <div class="row">
                <div class="col-6">
                    <h4>{{$entity1->name}}</h4>
                    <x-show-standard-details :item="$entity1"/>

                    <div class="row ml-1">
                        @foreach($entity1Activities as $e1activity)
                            <div class="col-10 bg-grey-9 mt-2">
                                @include('partials.activities.activity-card', ['activity' => $e1activity])
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-6">
                    <h4>{{$entity2->name}}</h4>
                    <x-show-standard-details :item="$entity2"/>

                    <div class="row ml-1">
                        @foreach($entity2Activities as $e2activity)
                            <div class="col-10  bg-grey-9 mt-2">
                                @include('partials.activities.activity-card', ['activity' => $e2activity])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection