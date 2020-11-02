@extends('layouts.app')

@section('pageTitle', 'View Sample')

@section('nav')
    @include('layouts.navs.app.project')
@stop


@section('content')
    <div x-data="initFilterProcesses()" class="here-i-am">
        @component('components.card')
            @slot('header')
                Compare sample {{$entity1->name}} to {{$entity2->name}}
                <a class="action-link float-right" href="#" @click="showHideFilter()">
                    <i class="fas fa-filter mr-2"></i>Filter Processes
                </a>
            @endslot

            @slot('body')
                <div class="row mb-2" style="display: none" x-show="showFilter">
                    <div class="col-5">
                        <ul class="list-unstyled">
                            @foreach($entity1Activities as $e1activity)
                                <li>
                                    <input type="checkbox" value="{{$e1activity->name}}">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-5">
                        @foreach($entity2Activities as $e2activity)
                            <li>
                                <input type="checkbox" value="{{$e2activity->name}}">
                            </li>
                        @endforeach
                    </div>
                </div>
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
    </div>
@endsection


@push('scripts')
    <script>
        function initFilterProcesses() {
            console.log('initFilterProcesses');
            return {
                showFilter: false,
                sample1Processes: [
                        @foreach($entity2Activities as $e2activity)
                    {
                        name: "{{$e2activity->name}}"
                    },
                    @endforeach
                ],

                sample2Processes: [
                        @foreach($entity2Activities as $e2activity)
                    {
                        name: "{{$e2activity->name}}"
                    },
                    @endforeach
                ],
                showHideFilter() {
                    console.log('showHideFilter');
                    this.showFilter = !this.showFilter;
                },
            };
        }
    </script>
@endpush