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
                <a class="action-link float-right" href="#" @click="showFilter = !showFilter">
                    <i class="fas fa-filter mr-2"></i>Filter Processes
                </a>
            @endslot

            @slot('body')
                <div style="display: none" x-show="showFilter">
                    <h4>Select/Deselect processes to show</h4>
                    <div class="row mb-2 mt-3">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                @foreach($entity1Activities as $e1activity)
                                    <li>
                                        <input type="checkbox" value="{{$e1activity->uuid}}"
                                               @click="toggleSample1Process($event)" checked>
                                        <label>{{$e1activity->name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                @foreach($entity2Activities as $e2activity)
                                    <li>
                                        <input type="checkbox" value="{{$e2activity->uuid}}"
                                               @click="toggleSample2Process($event)" checked>
                                        <label>{{$e2activity->name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h4>{{$entity1->name}}</h4>
                        <x-show-standard-details :item="$entity1"/>

                        <div class="row ml-1">
                            @foreach($entity1Activities as $e1activity)
                                <div class="col-10 bg-grey-9 mt-2" x-show="sample1Processes['{{$e1activity->uuid}}']">
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
                                <div class="col-10 bg-grey-9 mt-2" x-show="sample2Processes['{{$e2activity->uuid}}']">
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
            let xdata = {
                showFilter: false,
                sample1Processes: {},

                sample2Processes: {},

                toggleSample1Process(element) {
                    this.sample1Processes[element.target.value] = !this.sample1Processes[element.target.value];
                },

                toggleSample2Process(element) {
                    this.sample2Processes[element.target.value] = !this.sample2Processes[element.target.value];
                }
            };

            @foreach($entity1Activities as $e1activity)
                xdata.sample1Processes["{{$e1activity->uuid}}"] = true;
            @endforeach

                    @foreach($entity2Activities as $e2activity)
                xdata.sample2Processes["{{$e2activity->uuid}}"] = true;
            @endforeach


                return xdata;
        }
    </script>
@endpush