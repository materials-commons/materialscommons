@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop


@section('content')
    <div x-data="initFilterProcesses">
        <x-card>
            <x-slot name='header'>
                Compare sample {{$entity1->name}} to {{$entity2->name}}
                <a class="action-link float-right" href="#" @click="showFilter = !showFilter">
                    <i class="fas fa-filter mr-2"></i>Filter Processes
                </a>
            </x-slot>

            <x-slot name='body'>
                <div style="display: none" x-show="showFilter">
                    <h4>Select/Deselect processes to show <a href="#" class="ml-1"
                                                             @click="showFilter = false">(Hide)</a></h4>
                    <div class="row mb-2 mt-3">
                        <div class="col-6">
                            <div class="col-12 mb-3">
                                <a href="#" @click="selectAllSample1()">Select All</a>
                                <a href="#" class="ml-3" @click="deselectAllSample1()">Deselect All</a>
                            </div>
                            <ul class="list-unstyled">
                                @foreach($entity1Activities as $e1activity)
                                    <li>
                                        <input type="checkbox" value="{{$e1activity->uuid}}"
                                               @click="toggleSample1Process($event)"
                                               x-bind:checked="sample1Processes['{{$e1activity->uuid}}']">
                                        <label>{{$e1activity->name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="col-12 mb-3">
                                <a href="#" @click="selectAllSample2()">Select All</a>
                                <a href="#" class="ml-3" @click="deselectAllSample2()">Deselect All</a>
                            </div>
                            <ul class="list-unstyled">
                                @foreach($entity2Activities as $e2activity)
                                    <li>
                                        <input type="checkbox" value="{{$e2activity->uuid}}"
                                               @click="toggleSample2Process($event)"
                                               x-bind:checked="sample2Processes['{{$e2activity->uuid}}']">
                                        <label>{{$e2activity->name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-card>
                            <x-slot name="header">
                                <h5>{{$entity1->name}}</h5>
                            </x-slot>
                            <x-slot name="body">
                                <x-show-standard-details :item="$entity1"/>
                                <div class="row">
                                    @foreach($entity1Activities as $e1activity)
                                        <div class="col-12 tile mt-2"
                                             x-show="sample1Processes['{{$e1activity->uuid}}']">
                                            @include('partials.activities.activity-card', ['activity' => $e1activity])
                                        </div>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card>
                            <x-slot name="header">
                                <h5>{{$entity2->name}}</h5>
                            </x-slot>
                            <x-slot name="body">
                                <x-show-standard-details :item="$entity2"/>
                                <div class="row">
                                    @foreach($entity2Activities as $e2activity)
                                        <div class="col-12 tile mt-2"
                                             x-show="sample2Processes['{{$e2activity->uuid}}']">
                                            @include('partials.activities.activity-card', ['activity' => $e2activity])
                                        </div>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-card>
                    </div>
                </div>
            </x-slot>
        </x-card>
    </div>
@endsection


@push('scripts')
    <script>
        mcutil.onAlpineInit("initFilterProcesses", () => {
            let xdata = {
                showFilter: false,
                sample1Processes: {},

                sample2Processes: {},

                toggleSample1Process(element) {
                    this.sample1Processes[element.target.value] = !this.sample1Processes[element.target.value];
                },

                toggleSample2Process(element) {
                    this.sample2Processes[element.target.value] = !this.sample2Processes[element.target.value];
                },

                selectAllSample1() {
                    for (const key in this.sample1Processes) {
                        this.sample1Processes[key] = true;
                    }
                },

                deselectAllSample1() {
                    for (const key in this.sample1Processes) {
                        this.sample1Processes[key] = false;
                    }
                },

                selectAllSample2() {
                    for (const key in this.sample2Processes) {
                        this.sample2Processes[key] = true;
                    }
                },

                deselectAllSample2() {
                    for (const key in this.sample2Processes) {
                        this.sample2Processes[key] = false;
                    }
                },
            };

            @foreach($entity1Activities as $e1activity)
                xdata.sample1Processes["{{$e1activity->uuid}}"] = true;
            @endforeach

                    @foreach($entity2Activities as $e2activity)
                xdata.sample2Processes["{{$e2activity->uuid}}"] = true;
            @endforeach

                return xdata;
        });
    </script>
@endpush
