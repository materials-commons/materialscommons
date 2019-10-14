@extends('layouts.app')

@section('pageTitle', 'Edit Dataset')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.datasets.update', [$project, $dataset])}}"
                  id="dataset-create">
                @csrf
                @method('put')
                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$dataset->name}}"
                           placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              value="{{$dataset->description}}"
                              placeholder="Description..."></textarea>
                </div>
                <div class="form-group">
                    <label for="funding">Funding</label>
                    <input class="form-control" id="funding" name="funding" type="text"
                           value="{{$dataset->funding}}"
                           placeholder="Funding...">
                </div>
                <div class="form-group">
                    <label for="license">License</label>
                    <input class="form-control" id="license" name="license" type="text"
                           value="{{$dataset->license}}"
                           placeholder="License...">
                </div>
                <div class="form-group">
                    <label for="institution">Institution</label>
                    <input class="form-control" id="institution" name="institution" type="text"
                           value="{{$dataset->institution}}"
                           placeholder="Institution...">
                </div>
                <div class="form-group">
                    <label for="authors">Authors</label>
                    <input class="form-control" id="authors" name="authors" type="text"
                           value="{{$dataset->authors}}"
                           placeholder="Authors...">
                </div>
                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" id="next"
                       onclick="document.getElementById('dataset-create').submit()">
                        Next
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
    @push('scripts')
        <script>
            $(document).ready(() => {
                validate();
                $('#name').change(validate).keypress(() => validate());
            });

            function validate() {
                if ($('#name').val().length > 0) {
                    $("#next").prop("disabled", false).removeClass("isDisabled");
                } else {
                    $("#next").prop("disabled", true).addClass("isDisabled");
                }
            }
        </script>
    @endpush
@stop