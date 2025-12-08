@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop


@section('content')
    <div x-data="initFilterProcesses">
        {{-- Title and Filter Row --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4>Compare sample <strongx>{{$entity1->name}}</strongx> to <strongx>{{$entity2->name}}</strongx></h4>
                <a class="action-link text-nowrap" href="#" @click="showFilter = !showFilter">
                    <i class="fas fa-filter me-2"></i>Filter Processes
                </a>
            </div>
        </div>

        {{-- Compare Button Row --}}
        <div class="row mb-3">
            <div class="col-12">
                <button class="btn btn-primary" @click="compareActivities">Compare Activities</button>
            </div>
        </div>

        {{-- Filter Section --}}
        <div style="display: none" x-show="showFilter">
            <h4>Select/Deselect processes to show <a href="#" class="ms-1"
                                                     @click="showFilter = false">(Hide)</a></h4>
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <a href="#" @click="selectAllSample1()">Select All</a>
                        <a href="#" class="ms-3" @click="deselectAllSample1()">Deselect All</a>
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
                <div class="col-lg-6">
                    <div class="mb-3">
                        <a href="#" @click="selectAllSample2()">Select All</a>
                        <a href="#" class="ms-3" @click="deselectAllSample2()">Deselect All</a>
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

        {{-- Entity Names and Details Row --}}
        <div class="row mb-3">
            <div class="col-lg-6">
                <h5><strong>{{$entity1->name}}</strong></h5>
                <x-show-standard-details :item="$entity1"/>
            </div>
            <div class="col-lg-6">
                <h5><strong>{{$entity2->name}}</strong></h5>
                <x-show-standard-details :item="$entity2"/>
            </div>
        </div>

        {{-- Activity Cards Row --}}
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach($entity1Activities as $e1activity)
                        <div class="col-12" x-show="sample1Processes['{{$e1activity->uuid}}']">
                            <div class="white-box h-100">
                                <x-activities.activities-card :activity="$e1activity"
                                                              :user="$user"
                                                              :experiment="$entity1->experiments->first()"
                                                              :project="$project">
                                    <x-slot:header>
                                        <div class="d-flex float-end">
                                            <input type="radio" name="activity1_id"
                                                   value="{{$e1activity->id}}"
                                                   x-model="activity1Id"
                                                   class="me-2">
                                            <label class="mb-0">Select for comparison</label>
                                        </div>
                                    </x-slot:header>
                                </x-activities.activities-card>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach($entity2Activities as $e2activity)
                        <div class="col-12" x-show="sample2Processes['{{$e2activity->uuid}}']">
                            <div class="white-box h-100">
                                <x-activities.activities-card :activity="$e2activity"
                                                              :user="$user"
                                                              :experiment="$entity2->experiments->first()"
                                                              :project="$project">
                                    <x-slot:header>
                                        <div class="d-flex float-end">
                                            <input type="radio" name="activity2_id"
                                                   x-model="activity2Id"
                                                   value="{{$e2activity->id}}"
                                                   class="me-2">
                                            <label class="mb-0">Select for comparison</label>
                                        </div>
                                    </x-slot:header>
                                </x-activities.activities-card>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        mcutil.onAlpineInit("initFilterProcesses", () => {
            let xdata = {
                showFilter: false,
                sample1Processes: {},
                projectId: '{{$project->id}}',
                activity1Id: '',
                activity2Id: '',
                sample2Processes: {},

                compareActivities() {
                    if (this.activity1Id === '' || this.activity2Id === '') {
                        return;
                    }
                    window.location.href = route('projects.activities.compare', {
                        project: this.projectId,
                        activity1: this.activity1Id,
                        activity2: this.activity2Id,
                    });
                },

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
