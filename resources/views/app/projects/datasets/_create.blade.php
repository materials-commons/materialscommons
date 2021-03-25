<div class="float-right">
    <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
       class="btn btn-danger mr-3">
        Cancel
    </a>

    <a class="btn btn-info mr-3" href="#" id="save-button" onclick="setActionAndSubmit('save')">
        Save
    </a>

    <a class="btn btn-success mr-3" href="#" id="add-assets-button" onclick="setActionAndSubmit('assets')">
        Save And Add Data
    </a>
</div>
<br>
<br>
<form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset_create">
    @csrf
    <div class="form-group">
        <label class="required" for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Name...">
    </div>

    {{--    <x-datasets.create-authors-list :project="$project"></x-datasets.create-authors-list>--}}

    <x-datasets.create-authors-table :project="$project"/>
    <br>

    {{--    <table id="authors" class="table table-hover" style="width: 100%">--}}
    {{--        <thead>--}}
    {{--        <tr>--}}
    {{--            <th>Name</th>--}}
    {{--            <th>Affiliations</th>--}}
    {{--            <th>Email</th>--}}
    {{--            <th></th>--}}
    {{--        </tr>--}}
    {{--        </thead>--}}
    {{--        <tbody>--}}
    {{--        @foreach($project->team->members->merge($project->team->admins) as $author)--}}
    {{--            <tr>--}}
    {{--                <td>{{$author->name}}</td>--}}
    {{--                <td>{{$author->affiliations}}</td>--}}
    {{--                <td>{{$author->email}}</td>--}}
    {{--                <td></td>--}}
    {{--            </tr>--}}
    {{--        @endforeach--}}
    {{--        </tbody>--}}
    {{--    </table>--}}

    {{--    <div class="form-group">--}}
    {{--        <label for="authors">Authors and Affiliations</label>--}}
    {{--        <input class="form-control" id="authors" name="authors" type="text"--}}
    {{--               value="{{old('authors', $authorsAndAffiliations)}}"--}}
    {{--               placeholder="Authors...">--}}
    {{--    </div>--}}

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
        <label for="funding">Funding</label>
        <textarea class="form-control" id="funding" name="funding"
                  type="text" placeholder="Funding...">{{old('funding')}}</textarea>
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

    <x-datasets.create-papers-list :existing="null"/>

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

        <a class="action-link mr-3" href="#" id="save-button" onclick="setActionAndSubmit('save')">
            Save
        </a>

        <a class="action-link mr-3" href="#" id="add-assets-button"
           onclick="setActionAndSubmit('assets')">
            Save And Add Data
        </a>
    </div>
</form>
<br>
@include('common.errors')

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
                $("#add-assets-button").prop("disabled", true).addClass("isDisabled");
            } else {
                $("#save-button").prop("disabled", false).removeClass("isDisabled");
                $("#add-assets-button").prop("disabled", false).removeClass("isDisabled");
            }
        }

        function setActionAndSubmit(action) {
            $('#action').val(action);
            document.getElementById('dataset_create').submit();
        }

        function changeActionAndSubmit() {
            $('#action').val('save');
            document.forms.dataset_create.action = "{{route('projects.datasets.create-doi', [$project])}}";
            document.forms.dataset_create.submit();
        }

    </script>
@endpush
