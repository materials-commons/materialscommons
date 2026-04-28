<div x-data="datasetCETabsDetails">
    {{-- Floating action bar — fixed at bottom of viewport --}}
    <div class="d-flex justify-content-end align-items-center gap-2 py-2 px-4"
         style="position:fixed; bottom:0; left:0; right:0; z-index:1040;
                background:white; border-top:1px solid #dee2e6;
                box-shadow:0 -2px 8px rgba(0,0,0,.1);">
        <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
           class="btn btn-sm btn-outline-secondary">
            Cancel
        </a>
        <a class="btn btn-sm btn-outline-primary" href="#" id="save-button-top" @click.prevent="setActionAndSubmit('save')">
            <i class="fas fa-save me-1"></i> Save
        </a>
        <a class="btn btn-sm btn-success" href="#" id="done-button-top" @click.prevent="setActionAndSubmit('done')">
            <i class="fas fa-check me-1"></i> Save &amp; Exit
        </a>
        @include('app.projects.datasets.ce-tabs._done-and-publish-button')
    </div>

    <form method="post"
          action="{{route('projects.datasets.update', [$project, $dataset, 'public' => $isPublic])}}"
          id="dataset_update"
          style="padding-bottom:4rem;">
        @csrf
        @method('put')

        <div class="mb-3">
            <label class="required" for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text"
                   value="{{old('name', $dataset->name)}}"
                   placeholder="Name...">
        </div>

        <div class="mb-3">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" value="{{old('summary', $dataset->summary)}}"
                   name="summary">
        </div>

        {{--    <div class="mb-3">--}}
        {{--        <label for="authors">Authors and Affiliations</label>--}}
        {{--        <input class="form-control" id="authors" name="authors" type="text"--}}
        {{--               value="{{old('authors', $dataset->authors)}}"--}}
        {{--               placeholder="Authors...">--}}
        {{--    </div>--}}

        <x-datasets.create-authors-table :project="$project" :dataset="$dataset"/>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
                      placeholder="Description...">{{old('description', $dataset->description)}}</textarea>
        </div>

        <div class="mb-3">
            <label for="funding">Funding</label>
            <textarea class="form-control" id="funding" name="funding" type="text"
                      placeholder="Funding...">{{old('funding', $dataset->funding)}}</textarea>
        </div>

        <div class="mb-3">
            <label for="doi">DOI</label>
            @if (empty($dataset->doi))
                <span class="col-6">
                (None)
                <a href="#" @click.prevent="changeActionAndSubmit()" class="ms-6 ps-6">
                    Assign DOI
                </a>
            </span>
            @else
                <input class="form-control" id="doi" name="doi" type="text"
                       value="{{$dataset->doi}}" readonly>
            @endif
        </div>

        <x-datasets.create-papers-list :existing="$dataset->papers"/>

        <div class="mb-3 col-8">
            <label for="license">License</label>
            <select name="license" id="ds-license" class="mb-2 form-select"
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

        <div class="mb-3 col-8">
            <label for="experiments">Studies</label>
            <select name="experiments[]" multiple id="ds-studies" title="Studies">
                @foreach($experiments as $experiment)
                    <option data-token="{{$experiment->id}}"
                            {{$datasetHasExperiment($experiment) ? 'selected' : ''}}
                            value="{{$experiment->id}}">{{$experiment->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-8">
            <label for="communities">Communities</label>
            <select name="communities[]" multiple id="ds-communities" title="Communities">
                @foreach($communities as $community)
                    <option data-token="{{$community->id}}"
                            {{$datasetHasCommunity($community) ? 'selected' : ''}}
                            value="{{$community->id}}">{{$community->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tags">Tags</label>
            <input class="form-control" id="tags" name="tags" value="{{old('tags', $tagsList)}}">
        </div>

        <input hidden id="project_id" name="project_id" value="{{$project->id}}">
        <input type="hidden" name="action" value="" id="action"/>
    </form>
</div>

@include('common.errors')

@push('scripts')
    <script>
        $(document).ready(() => {

            new TomSelect('#ds-license', {
                // controlInput: null,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });

            new TomSelect('#ds-studies', {
                plugins: ['dropdown_input'],
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });

            new TomSelect('#ds-communities', {
                sortField: {
                    field: "text",
                    direction: "asc"
                },
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
                    $("#add-assets-button").prop("disabled", true).addClass("isDisabled");
                } else {
                    $("#save-button").prop("disabled", false).removeClass("isDisabled");
                    $("#add-assets-button").prop("disabled", false).removeClass("isDisabled");
                }
            }

            validate();
            $('#name').change(validate).keypress(() => validate());
            let tagsInput = document.querySelector('#tags');
            new Tagify(tagsInput);

            // Intercept nav-tab clicks: save the form first, then let the controller redirect
            // to the clicked tab. The controller supports actions: 'files', 'workflow', 'samples'.
            $('.nav-tabs .nav-link').not('.active').on('click', function (e) {
                e.preventDefault();
                const href = $(this).attr('href') || '';
                let action = 'save';
                if (href.includes('/files'))     action = 'files';
                else if (href.includes('/workflows')) action = 'workflow';
                else if (href.includes('/samples'))   action = 'samples';

                _serializeAndSubmit(action);
            });

            // Shared helper — submit the form with the given action
            function _serializeAndSubmit(action) {
                $('#action').val(action);
                document.getElementById('dataset_update').submit();
            }

            // Expose to Alpine component
            window._dsDetailsSerializeAndSubmit = _serializeAndSubmit;
        });

        mcutil.onAlpineInit("datasetCETabsDetails", () => {
            return {
                validate() {
                    if ($('#name').val().length > 0) {
                        setNextButtonsDisabled(false);
                    } else {
                        setNextButtonsDisabled(true);
                    }
                },

                setNextButtonsDisabled(disable) {
                    if (disable) {
                        $("#save-button").prop("disabled", true).addClass("isDisabled");
                        $("#save-button-top").prop("disabled", true).addClass("isDisabled");
                        $("#done-button").prop("disabled", true).addClass("isDisabled");
                        $("#done-button-top").prop("disabled", true).addClass("isDisabled");
                        $("#add-samples-button").prop("disabled", true).addClass("isDisabled");
                        $("#add-processes-button").prop("disabled", true).addClass("isDisabled");
                        $("#add-files-button").prop("disabled", true).addClass("isDisabled");
                        $("#add-workflow-button").prop("disabled", true).addClass("isDisabled");
                    } else {
                        $("#save-button").prop("disabled", false).removeClass("isDisabled");
                        $("#save-button-top").prop("disabled", false).removeClass("isDisabled");
                        $("#done-button").prop("disabled", false).removeClass("isDisabled");
                        $("#done-button-top").prop("disabled", false).removeClass("isDisabled");
                        $("#add-samples-button").prop("disabled", false).removeClass("isDisabled");
                        $("#add-processes-button").prop("disabled", false).removeClass("isDisabled");
                        $("#add-files-button").prop("disabled", false).removeClass("isDisabled");
                        $("#add-workflow-button").prop("disabled", false).removeClass("isDisabled");
                    }
                },

                setActionAndSubmit(action) {
                    // Delegate to the shared helper defined in $(document).ready
                    if (window._dsDetailsSerializeAndSubmit) {
                        window._dsDetailsSerializeAndSubmit(action);
                    }
                },

                changeActionAndSubmit() {
                    $('#action').val('save');
                    document.forms.dataset_update.action = "{{route('projects.datasets.assign-doi', [$project, $dataset])}}";
                    document.forms.dataset_update.submit();
                }
            }
        });
    </script>
@endpush
