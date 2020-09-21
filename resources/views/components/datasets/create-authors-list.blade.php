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
                        {{$project->owner->name}} (Owner)
                        <input hidden name="author_order[]" value="{{$project->owner->email}}" type="text">
                        {{--                        <sup>1</sup>--}}
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
                                {{$author->name}}
                                <input hidden name="author_order[]" value="{{$author->email}}" type="text">
                                {{--                                <sup>1</sup>--}}
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
    {{--    <h3>Organizations</h3>--}}
    {{--    <ul class="list-unstyled" id="organizations">--}}
    {{--        @foreach($project->team->members->merge($project->team->admins)->pluck('affiliations')->filter()->unique() as $org)--}}
    {{--            <li>{{$loop->iteration}}. {{$org}}</li>--}}
    {{--        @endforeach--}}
    {{--    </ul>--}}
</div>

<div class="form-group">
    <label for="additional_authors">Additional Authors</label>
    <div class="">
        <a href="#" onclick="addAdditionalAuthor()"><i class="fa fas fa-fw fa-plus"></i>Add External Author</a>
        <ul class="list-unstyled" id="additional_authors"></ul>
    </div>
</div>

@push('scripts')
    <script>

        let nextAdditionalAuthorId = 100;
        let knownOrgs = [];
        let orgToUserMap = [];

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
                    <!-- <div class="col">
                        <input class="form-control" name="additional_authors['id_${nextAdditionalAuthorId}'][orcid]"
                            type="text" placeholder="ORCID ID...(Optional)"
                            required id="orcid_${nextAdditionalAuthorId}">
                    </div> -->
                </div>
            </li>`);
            nextAdditionalAuthorId++;
        }

        function removeAuthor(id) {
            $(`#${id}`).remove();
        }

        function addExternalUser(id) {
            let org = $(`#org_${id}`)[0].value;
            let name = $(`#name_${id}`)[0].value;
            let email = $(`#email_${id}`)[0].value;
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
                            ${name}
                            <input hidden name="author_order[]" value="${email}" type="text">
                        </li>`);
            $('#select-authors').append(`<li id="ex_sel_${id}">
                    <a class="action ml-3" href="#" onclick="removeExternalUser('${id}')">
                        <i class="fa fa-fw fas fa-trash"></i>
                    </a> ${name} (External Author)`);
            $(`#${id}`).hide();
        }

        function removeExternalUser(id) {
            $(`#ex_${id}`).remove();
            $(`#ex_sel_${id}`).remove();
        }
    </script>
@endpush