<form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset_create">
    @csrf
    <div class="form-group">
        <label class="required" for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Name...">
    </div>

    <div class="form-group">
        <div class="row">
            <label for="authors">Authors</label>
            <div class="drag-and-drop col-12" x-data="{adding: false, removing: false}">
                <div class="drag-and-drop__container drag-and-drop__container--from">
                    <h3 class="drag-and-drop__title">Dataset Authors (drag to re-order)</h3>
                    <ul class="drag-and-drop__items list-unstyled" id="dataset-authors"
                        :class="{'drag-and-drop__items--removing': removing}"
                        x-on:drop="removing = false"
                        x-on:drop.prevent="
                        const id = event.dataTransfer.getData('text/plain');
                        const target = event.target.closest('ul');
                        const element = document.getElementById(id);
                        target.appendChild(element);
                    "
                        x-on:dragover.prevent="removing = true"
                        x-on:dragleave.prevent="removing = false">
                        <li id="{{$project->owner->uuid}}"
                            class="drag-and-drop__item"
                            :class="{'drag-and-drop__item--dragging': dragging}"
                            x-data="{dragging: false}"
                            x-on:dragstart.self="
                            dragging = true;
                            event.dataTransfer.effectAllowed = 'move';
                            event.dataTransfer.setData('text/plain', event.target.id);
                        "
                            x-on:dragend="dragging = false"
                            draggable="true">
                            <span><i class="fa fas fa-fw fa-user"></i></span>
                            {{$project->owner->name}} (Owner)<sup>1</sup>
                        </li>
                        @foreach($project->team->members->merge($project->team->admins)->sortBy('name') as $author)
                            @if($author->id != auth()->id())
                                <li id="{{$author->uuid}}"
                                    class="drag-and-drop__item"
                                    :class="{'drag-and-drop__item--dragging': dragging}"
                                    x-data="{dragging: false}"
                                    x-on:dragstart.self="
                                    dragging = true;
                                    event.dataTransfer.effectAllowed = 'move';
                                    event.dataTransfer.setData('text/plain', event.target.id);
                                "
                                    x-on:dragend="dragging = false"
                                    draggable="true">
                                    {{$author->name}}<sup>1</sup>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <h3>Select Authors (from project and external authors)</h3>
        <ul class="list-unstyled" id="select-authors">
            <li>
                <span class="ml-3"><i class="fa fas fa-fw fa-user"></i></span>
                {{$project->owner->name}} (Owner)
                <input name="mc_authors[]" value="{{$project->owner->id}}" type="text" hidden>
            @foreach($project->team->members->merge($project->team->admins)->sortBy('name') as $author)
                @if($author->id != auth()->id())
                    <li>
                        <div style="margin-left: 2.15rem;">
                            <input type="checkbox" class="form-check-input"
                                   onclick="checkboxClicked(this, '{{$author->uuid}}', '{{$author->name}}')"
                                   value="{{$author->id}}" name="mc_authors[]" checked>
                            {{$author->name}}
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
        <h3>Organizations</h3>
        <ul class="list-unstyled" id="organizations">
            @foreach($project->team->members->merge($project->team->admins)->pluck('affiliations')->filter()->unique() as $org)
                <li>{{$loop->iteration}}. {{$org}}</li>
            @endforeach
        </ul>
    </div>

    <div class="form-group">
        <label for="additional_authors">Additional Authors</label>
        <div class="">
            <a href="#" onclick="addAdditionalAuthor()"><i class="fa fas fa-fw fa-plus"></i>Add External Author</a>
            <ul class="list-unstyled" id="additional_authors"></ul>
        </div>
    </div>

    <div class="form-group">
        <label for="summary">Summary</label>
        <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
               placeholder="Summary...">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  value=""
                  placeholder="Description...">{{old('description')}}</textarea>
    </div>

    <div class="form-group">
        <label for="doi">DOI</label>
        <span class="col-8">
            None
            <a href="#" onclick="changeActionAndSubmit()" style="margin-left: 8px">
                Assign DOI
            </a>
        </span>
    </div>

    <div class="form-group">
        <label for="license">License</label>
        <select name="license" class="selectpicker col-lg-8" data-live-search="true"
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
        <div class="form-group">
            <p>
                Selecting experiments will automatically add all the samples for the experiments to
                the dataset
            </p>
            <label for="experiments">Experiments</label>
            <select name="experiments[]" class="selectpicker col-lg-8"
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

    <div class="form-group">
        <label for="communities">Communities</label>
        <select name="communities[]" class="selectpicker col-lg-8"
                title="communities"
                data-live-search="true" multiple>
            @foreach($communities as $community)
                <option data-token="{{$community->id}}" value="{{$community->id}}">
                    {{$community->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tags">Tags</label>
        <input class="form-control" id="tags" name="tags" value="{{old('tags')}}">
    </div>

    <input hidden id="project_id" name="project_id" value="{{$project->id}}">
    <input type="hidden" name="action" value="" id="action"/>

    <div class="float-right">
        <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
           class="action-link danger mr-3">
            Cancel
        </a>

        <a class="action-link mr-3" href="#" id="save-button" onclick="validateSetActionAndSubmit('save')">
            Save
        </a>

        <a class="action-link mr-3" href="#" id="add-assets-button"
           onclick="validateSetActionAndSubmit('assets')">
            Save And Add Data
        </a>
    </div>
</form>
<br>
@include('common.errors')

@push('scripts')
    <script>
        let nextAdditionalAuthorId = 100;
        $(document).ready(() => {
            validate();
            $('#name').change(validate).keypress(() => validate());

            let tagsInput = document.querySelector('#tags');
            new Tagify(tagsInput);
        });

        function validate() {
            if (isValid()) {
                setNextButtonsDisabled(false);
            } else {
                setNextButtonsDisabled(true);
            }
        }

        function isValid() {
            let formData = $('#dataset_create').serializeArray();
            for (let i = 100; i > nextAdditionalAuthorId; i++) {
                let nameValue = formData.find((item) => item['name'] === `additional_authors['id_${i}'][name]`);
                let emailValue = formData.find((item) => item['name'] === `additional_authors['id_${i}'][email]`);
                let affiliationsValue = formData.find((item) => item['name'] === `additional_authors['id_${i}'][affiliations]`);
                if (nameValue === "") {
                    return false;
                }

                if (emailValue === "") {
                    return false;
                }

                if (affiliationsValue === "") {
                    return false;
                }
            }
            let additionalAuthors;
            return ($('#name').val().length > 0);
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

        function validateSetActionAndSubmit(action) {
            if (!isValid()) {
                return;
            }
            $('#action').val(action);
            document.getElementById('dataset_create').submit();
        }

        function changeActionAndSubmit() {
            $('#action').val('save');
            document.forms.dataset_create.action = "{{route('projects.datasets.create-doi', [$project])}}";
            document.forms.dataset_create.submit();
        }

        function removeAuthor(id) {
            $(`#${id}`).remove();
        }

        function addAdditionalAuthor() {
            $('#additional_authors').append(`<li id="${nextAdditionalAuthorId}">
                <div class="form-row mt-2">
                    <a href="#" onclick="removeAuthor('${nextAdditionalAuthorId}')"><i class="fa fas fa-fw fa-trash"></i></a>
                    <div class="col">
                        <input class="form-control" name="additional_authors['id_${nextAdditionalAuthorId}'][name]" type="text" placeholder="Name...(Required)"
                                id="name_${nextAdditionalAuthorId}" required>
                    </div>
                    <div class="col">
                        <input class="form-control" name="additional_authors['id_${nextAdditionalAuthorId}'][email]" type="email"
                            placeholder="Email...(Required)" id="email_${nextAdditionalAuthorId}" required>
                    </div>
                    <div class="col">
                        <input class="form-control" name="additional_authors['id_${nextAdditionalAuthorId}'][affiliations]"
                            type="text" placeholder="Organization...(Required)"
                            required id="org_${nextAdditionalAuthorId}">
                        <a class="action" href="#" onclick="addExternalUser('${nextAdditionalAuthorId}')">Done</a>
                    </div>
                </div>
            </li>`);
            nextAdditionalAuthorId++;
        }

        function checkboxClicked(element, id, name) {
            if (element.checked) {
                $('#dataset-authors').append(`<li id="${id}"
                                        x-data="{dragging: false}"
                                        class="drag-and-drop__item"
                                        :class="{ 'drag-and-drop__item--dragging': dragging}"
                                        x-on:dragstart.self="
                                            dragging = true;
                                            event.dataTransfer.effectAllowed = 'move';
                                            event.dataTransfer.setData('text/plain', event.target.id);
                                        "
                                        x-on:dragend="dragging = false"
                                        draggable="true">
                                            ${name}
                                    </li>`);
            } else {
                $(`#${id}`).remove();
            }
        }

        function addExternalUser(id) {
            let org = $(`#org_${id}`)[0].value;
            let name = $(`#name_${id}`)[0].value;
            $('#organizations').append(`<li>2. ${org}</li>`);
            $('#dataset-authors').append(`<li id="ex_${id}"
                                        x-data="{dragging: false}"
                                        class="drag-and-drop__item"
                                        :class="{ 'drag-and-drop__item--dragging': dragging}"
                                        x-on:dragstart.self="
                                            dragging = true;
                                            event.dataTransfer.effectAllowed = 'move';
                                            event.dataTransfer.setData('text/plain', event.target.id);
                                        "
                                        x-on:dragend="dragging = false"
                                        draggable="true">
                            ${name}<sup>2</sup>
                        </li>`);
            $('#select-authors').append(`<li><a class="action ml-3" href="#"><i class="fa fa-fw fas fa-trash"></i></a> ${name} (External Author)`);
            $(`#${id}`).hide();
        }

        /*
        <li>
                        <div style="margin-left: 2.15rem;">
                            <input type="checkbox" class="form-check-input"
                                   onclick="checkboxClicked(this, '{{$author->uuid}}', '{{$author->name}}')"
                                   value="{{$author->id}}" name="mc_authors[]" checked>
                            {{$author->name}}
        </div>
    </li>
*/

    </script>
@endpush