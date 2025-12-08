@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    <h3>Select Project Step</h3>
    <h5>Select Project</h5>
    <form class="col-8">
        <div class="mb-3">
            <label for="projects">Projects</label>
            <select id="select-project">
                @foreach($projects as $project)
                    <option data-token="{{$project->id}}" value="{{$project->id}}">
                        {{$project->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-10" x-data="selectProject">
            <div class="float-end">
                <a class="action-link danger me-3" href="#">Cancel</a>
                <a class="action-link me-3" href="#" @click.prevent="gotoCreateDataset()">Use Selected
                    Project</a>
            </div>
        </div>
    </form>
@stop

@push('scripts')
    <script>
        mcutil.onAlpineInit("selectProject", () => {
            new TomSelect('#select-project', {
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
            return {
                gotoCreateDataset() {
                    let projectId = $('#select-project').val();
                    window.location.href = route('projects.datasets.create', {project: projectId});
                }
            }
        });
    </script>
@endpush
