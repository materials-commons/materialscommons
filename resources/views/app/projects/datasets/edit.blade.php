@extends('layouts.app')

@section('pageTitle', 'Edit Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.edit', $project, $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            Edit Dataset
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.datasets.update', [$project, $dataset])}}"
                  id="dataset-update">
                @csrf
                @method('put')

                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$dataset->name}}"
                           placeholder="Name...">
                </div>

                <div class="form-group">
                    <label for="authors">Authors and Affiliations</label>
                    <input class="form-control" id="authors" name="authors" type="text"
                           value="{{$dataset->authors}}"
                           placeholder="Authors...">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              value="{{$dataset->description}}"
                              placeholder="Description...">{{$dataset->description}}</textarea>
                </div>

                <div class="form-group">
                    <label for="doi">DOI</label>
                    @if (empty($dataset->doi))
                        <span class="col-6">
                            (None)
                            <a href="{{route('projects.datasets.assign-doi', [$project, $dataset])}}"
                               class="ml-6 pl-6">
                                Assign DOI
                            </a>
                        </span>
                    @else
                        <input class="form-control" id="doi" name="doi" type="text"
                               value="{{$dataset->doi}}" readonly>
                    @endif
                </div>
                <div class="form-group">
                    <label for="funding">Funding</label>
                    <input class="form-control" id="funding" name="funding" type="text"
                           value="{{$dataset->funding}}"
                           placeholder="Funding...">
                </div>
                <div class="form-group">
                    <label for="license">License</label>
                    <select name="license" class="selectpicker col-lg-8" data-live-search="true"
                            value="{{$dataset->license}}"
                            title="License">
                        <option data-token="No License" value="No License"
                                {{$dataset->license === "No License" ? 'selected' : ''}}>
                            No License
                        </option>
                        <option data-token="Public Domain Dedication and License (PDDL)"
                                value="Public Domain Dedication and License (PDDL)"
                                {{$dataset->license === "Public Domain Dedication and License (PDDL)" ? 'selected' : ''}}>
                            Public Domain Dedication and License (PDDL)
                        </option>
                        <option data-token="Attribution License (ODC-By)"
                                value="Attribution License (ODC-By)"
                                {{$dataset->license === "Attribution License (ODC-By)" ? 'selected' : ''}}>
                            Attribution License (ODC-By)
                        </option>
                        <option data-token="Open Database License (ODC-ODbL)"
                                value="Open Database License (ODC-ODbL)"
                                {{$dataset->license === "Open Database License (ODC-ODbL)" ? 'selected' : ''}}>
                            Open Database License (ODC-ODbL)
                        </option>
                    </select>
                    <a href="https://opendatacommons.org/licenses/index.html" target="_blank">License Summaries</a>
                </div>

                <div class="form-group">
                    <label for="experiments">Experiments</label>
                    <select name="experiments[]" class="selectpicker col-lg-8" data-live-search="true" multiple
                            title="Experiments">
                        @foreach($experiments as $experiment)
                            <option data-token="{{$experiment->id}}"
                                    {{$datasetHasExperiment($experiment) ? 'selected' : ''}}
                                    value="{{$experiment->id}}">{{$experiment->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="communities">Communities</label>
                    <select name="communities[]" class="selectpicker col-lg-8" data-live-search="true" multiple
                            title="Communities">
                        @foreach($communities as $community)
                            <option data-token="{{$community->id}}"
                                    {{$datasetHasCommunity($community) ? 'selected' : ''}}
                                    value="{{$community->id}}">{{$community->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input class="form-control" id="tags" name="tags" value="{{$tagsList}}">
                </div>

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <input type="hidden" name="action" value="" id="action"/>

                <div class="float-right">
                    <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link mr-3" href="#" id="save-button" onclick="setActionAndSubmit('save')">
                        Done
                    </a>
                </div>
            </form>

            @include('common.errors')

            <br>
            <br>
            @include('app.projects.datasets.edit-tabs.tabs')
            <br>

            @if (Request::routeIs('projects.datasets.edit'))
                @include('app.projects.datasets.edit-tabs.files')
            @elseif (Request::routeIs('projects.datasets.samples.edit'))
                @include('app.projects.datasets.edit-tabs.entities')
            @elseif (Request::routeIs('projects.datasets.activities.edit'))
                @include('app.projects.datasets.edit-tabs.activities')
            @elseif (Request::routeIs('projects.datasets.workflows.edit'))
                @include('app.projects.datasets.edit-tabs.workflows')
            @endif

        @endslot
    @endcomponent
    @push('scripts')
        <script>
            $(document).ready(() => {
                validate();
                $('#name').change(validate).keypress(() => validate());
                let tagsInput = document.querySelector('#tags');
                new Tagify(tagsInput);
            });

            function validate() {
                if ($('#name').val().length > 0) {
                    setNextButtonsDisabled(false);
                } else {
                    setNextButtonsDisabled(true);
                }
            }

            function setNextButtonsDisabled(disable) {
                if (disable) {
                    $("#save-button").prop("disabled", true).addClass("isDisabled");
                    $("#add-samples-button").prop("disabled", true).addClass("isDisabled");
                    $("#add-processes-button").prop("disabled", true).addClass("isDisabled");
                    $("#add-files-button").prop("disabled", true).addClass("isDisabled");
                    $("#add-workflow-button").prop("disabled", true).addClass("isDisabled");
                } else {
                    $("#save-button").prop("disabled", false).removeClass("isDisabled");
                    $("#add-samples-button").prop("disabled", false).removeClass("isDisabled");
                    $("#add-processes-button").prop("disabled", false).removeClass("isDisabled");
                    $("#add-files-button").prop("disabled", false).removeClass("isDisabled");
                    $("#add-workflow-button").prop("disabled", false).removeClass("isDisabled");
                }
            }

            function setActionAndSubmit(action) {
                $('#action').val(action);
                document.getElementById('dataset-update').submit();
            }
        </script>
    @endpush
@stop
