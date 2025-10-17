<div x-data="datasets_create">
    <div class="float-right">
        <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
           class="btn btn-danger me-3">
            Cancel
        </a>

        <a class="btn btn-info me-3" href="#" id="save-button" @click.prevent="setActionAndSubmit('save')">
            Save
        </a>

        <a class="btn btn-success me-3" href="#" id="add-assets-button" @click.prevent="setActionAndSubmit('assets')">
            Save And Add Data
        </a>
    </div>
    <br>
    <br>
    <form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset_create">
        @csrf
        <div class="mb-3">
            <label class="required" for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                   placeholder="Name...">
        </div>

        <x-datasets.create-authors-table :project="$project" :dataset="null"/>
        <div id="authors_list"></div>
        <br>

        {{--    <div class="mb-3">--}}
        {{--        <label for="authors">Authors and Affiliations</label>--}}
        {{--        <input class="form-control" id="authors" name="authors" type="text"--}}
        {{--               value="{{old('authors', $authorsAndAffiliations)}}"--}}
        {{--               placeholder="Authors...">--}}
        {{--    </div>--}}

        <div class="mb-3">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
                   placeholder="Summary...">
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
                      value=""
                      placeholder="Description...">{{old('description')}}</textarea>
        </div>

        <div class="mb-3">
            <label for="funding">Funding</label>
            <textarea class="form-control" id="funding" name="funding"
                      type="text" placeholder="Funding...">{{old('funding')}}</textarea>
        </div>

        <div class="mb-3">
            <label for="doi">DOI</label>
            <span class="col-8">
            None
            <a href="#" @click.prevent="changeActionAndSubmit()" style="margin-left: 8px">
                Assign DOI
            </a>
        </span>
        </div>

        <x-datasets.create-papers-list :existing="null"/>

        <div class="mb-3">
            <label for="license">License</label>
            <select name="license" class="selectpicker col-lg-8" data-live-search="true"
                    data-style="btn-light no-tt"
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

        @if($experiments->isNotEmpty())
            <div class="mb-3">
                <label for="experiments">Studies</label>
                <select name="experiments[]" class="selectpicker col-lg-8"
                        data-style="btn-light no-tt"
                        title="experiments"
                        data-live-search="true" multiple>
                    @foreach($experiments as $experiment)
                        <option data-token="{{$experiment->id}}" value="{{$experiment->id}}">
                            {{$experiment->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mb-3">
            <label for="communities">Communities</label>
            <select name="communities[]" class="selectpicker col-lg-8"
                    data-style="btn-light no-tt"
                    title="communities"
                    data-live-search="true" multiple>
                @foreach($communities as $community)
                    <option data-token="{{$community->id}}" value="{{$community->id}}">
                        {{$community->name}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tags">Tags</label>
            <input class="form-control" id="tags" name="tags" value="{{old('tags')}}">
        </div>

        <input hidden id="project_id" name="project_id" value="{{$project->id}}">
        <input type="hidden" name="action" value="" id="action"/>

        <div class="float-right">
            <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
               class="action-link danger me-3">
                Cancel
            </a>

            <a class="action-link me-3" href="#" id="save-button" @click.prevent="setActionAndSubmit('save')">
                Save
            </a>

            <a class="action-link me-3" href="#" id="add-assets-button"
               @click.prevent="setActionAndSubmit('assets')">
                Save And Add Data
            </a>
        </div>
    </form>
</div>
<br>
@include('common.errors')

@push('scripts')
    <script>
        $(document).ready(() => {
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
        });

        mcutil.onAlpineInit("datasets_create", () => {
            return {
                setActionAndSubmit(action) {
                    $('#action').val(action);
                    let authorsListElement = document.getElementById('authors_list');

                    let values = [];
                    let authorTable = $("#authors").DataTable();
                    authorTable.rows().data().each(row => values.push(this.createAuthorElement(row[2], row[3], row[4])));
                    for (let i = 0; i < values.length; i++) {
                        let author = values[i];
                        let nameInput = document.createElement("input");
                        nameInput.type = "hidden";
                        nameInput.name = `ds_authors[${i}][name]`;
                        nameInput.value = author.name;
                        authorsListElement.appendChild(nameInput);

                        let emailInput = document.createElement("input");
                        emailInput.type = "hidden";
                        emailInput.name = `ds_authors[${i}][email]`;
                        emailInput.value = author.email;
                        authorsListElement.appendChild(emailInput);

                        let affiliationsInput = document.createElement("input");
                        affiliationsInput.type = "hidden";
                        affiliationsInput.name = `ds_authors[${i}][affiliations]`;
                        affiliationsInput.value = author.affiliations;
                        authorsListElement.appendChild(affiliationsInput);
                    }
                    document.getElementById('dataset_create').submit();
                    if (action === 'done') {
                        window.location.href = "{{route('projects.datasets.index', ['project' => $project->id])}}";
                    }
                },

                createAuthorElement(name, affiliations, email) {
                    return {
                        name: name,
                        affiliations: affiliations,
                        email: email,
                    };
                },

                changeActionAndSubmit() {
                    $('#action').val('save');
                    document.forms.dataset_create.action = "{{route('projects.datasets.create-doi', [$project])}}";
                    document.forms.dataset_create.submit();
                }
            }
        });

    </script>
@endpush
