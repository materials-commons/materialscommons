@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Select Project Step
        @endslot

        @slot('body')
            <h5>Select Project</h5>
            <form class="col-8">
                <div class="form-group">
                    <label for="projects">Projects</label>
                    <select name="project" class="selectpicker col-9" title="projects"
                            data-live-search="true" id="select-project">
                        @foreach($projects as $project)
                            <option data-token="{{$project->id}}" value="{{$project->id}}">
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-10">
                    <div class="float-right">
                        <a class="action-link danger mr-3" href="#">Cancel</a>
                        <a class="action-link mr-3" href="#" onclick="gotoCreateDataset()">Use Selected Project</a>
                    </div>
                </div>
            </form>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        let projectId = null;
        $(document).ready(() => {
            $('#select-project').on('changed.bs.select', function (e) {
                projectId = e.target.value;
            });
        });

        function gotoCreateDataset() {
            window.location.href = route('projects.datasets.create', {project: projectId});
        }
    </script>
@endpush
