@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    <x-card>
        <x-slot:header>
            Select Project Step
        </x-slot:header>

        <x-slot:body>
            <h5>Select Project</h5>
            <form class="col-8">
                <div class="mb-3">
                    <label for="projects">Projects</label>
                    <select name="project" class="selectpicker col-9" title="projects"
                            data-style="btn-light no-tt"
                            data-live-search="true" id="select-project">
                        @foreach($projects as $project)
                            <option data-token="{{$project->id}}" value="{{$project->id}}">
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-10" x-data="selectProject">
                    <div class="float-right">
                        <a class="action-link danger me-3" href="#">Cancel</a>
                        <a class="action-link me-3" href="#" @click.prevent="gotoCreateDataset()">Use Selected
                            Project</a>
                    </div>
                </div>
            </form>
        </x-slot:body>
    </x-card>
@stop

@push('scripts')
    <script>
        mcutil.onAlpineInit("selectProject", () => {
            return {
                gotoCreateDataset() {
                    let projectId = $('#select-project').val();
                    window.location.href = route('projects.datasets.create', {project: projectId});
                }
            }
        });
    </script>
@endpush
