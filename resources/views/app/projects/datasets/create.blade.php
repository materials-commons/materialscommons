@extends('layouts.app')

@section('pageTitle', 'Create Dataset')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Dataset
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset-create">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value=""
                           placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              value=""
                              placeholder="Description..."></textarea>
                </div>
                <div class="form-group">
                    <label for="funding">Funding</label>
                    <input class="form-control" id="funding" name="funding" type="text" value=""
                           placeholder="Funding...">
                </div>
                <div class="form-group">
                    <label for="license">License</label>
                    <select name="license" class="selectpicker col-lg-10" data-live-search="true"
                            title="License">
                        <option data-token="No License" value="No License">No License</option>
                        <option data-token="Public Domain Dedication and License (PDDL)"
                                value="Public Domain Dedication and License (PDDL)">
                            Public Domain Dedication and License (PDDL)
                        </option>
                        <option data-token="Attribution License (ODC-By)"
                                value="Attribution License (ODC-By)">
                            Attribution License (ODC-By)
                        </option>
                        <option data-token="Open Database License (ODC-ODbL)"
                                value="Open Database License (ODC-ODbL)">
                            Open Database License (ODC-ODbL)
                        </option>
                    </select>
                    <a href="https://opendatacommons.org/licenses/index.html" target="_blank">License Summaries</a>
                </div>
                <div class="form-group">
                    <label for="institution">Institution</label>
                    <input class="form-control" id="institution" name="institution" type="text"
                           value="" placeholder="Institution...">
                </div>
                <div class="form-group">
                    <label for="authors">Authors</label>
                    <input class="form-control" id="authors" name="authors" type="text" value=""
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