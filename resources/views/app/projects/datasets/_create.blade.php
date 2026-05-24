<div x-data="datasets_create">
    {{-- Floating action bar --}}
    <div class="d-flex justify-content-end align-items-center gap-2 py-2 px-4"
         style="position:fixed; bottom:0; left:0; right:0; z-index:1040;
                background:white; border-top:1px solid #dee2e6;
                box-shadow:0 -2px 8px rgba(0,0,0,.1);">
        <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
           class="btn btn-sm btn-outline-secondary">
            Cancel
        </a>
        <a class="btn btn-sm btn-outline-primary" href="#" id="save-button" @click.prevent="setActionAndSubmit('save')">
            <i class="fas fa-save me-1"></i> Save
        </a>
        <a class="btn btn-sm btn-success" href="#" id="add-assets-button" @click.prevent="setActionAndSubmit('assets')">
            <i class="fas fa-arrow-right me-1"></i> Save &amp; Add Files
        </a>
    </div>

    <form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset_create"
          style="padding-bottom:4rem;">
        @csrf

        <div class="mb-3">
            <label class="required" for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                   placeholder="Name...">
        </div>

        <div class="mb-3">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
                   placeholder="Summary...">
        </div>

        <x-datasets.create-authors-table :project="$project" :dataset="null"/>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
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
                <a href="#" @click.prevent="changeActionAndSubmit()" style="margin-left:8px;">
                    Assign DOI
                </a>
            </span>
        </div>

        <x-datasets.create-papers-list :existing="null"/>

        <x-datasets.license-picker :current-license="old('license', 'CC BY 4.0')"/>

        @if($experiments->isNotEmpty())
            <div class="mb-3 col-8">
                <label for="experiments">Studies</label>
                <select name="experiments[]" id="ds-studies" class="form-select mb-2" title="experiments" multiple>
                    @foreach($experiments as $experiment)
                        <option data-token="{{$experiment->id}}" value="{{$experiment->id}}">
                            {{$experiment->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mb-3 col-8">
            <label for="communities">Communities</label>
            <select name="communities[]" id="ds-communities" class="form-select mb-2" title="communities" multiple>
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
    </form>
</div>

@include('common.errors')

@push('scripts')
    <script>
        $(document).ready(() => {
            @if($experiments->isNotEmpty())
            new TomSelect('#ds-studies', {
                plugins: ['dropdown_input'],
                sortField: {field: "text", direction: "asc"},
            });
            @endif

            new TomSelect('#ds-communities', {
                sortField: {field: "text", direction: "asc"},
            });

            function validate() {
                const hasName = $('#name').val().length > 0;
                $("#save-button").prop("disabled", !hasName).toggleClass("isDisabled", !hasName);
                $("#add-assets-button").prop("disabled", !hasName).toggleClass("isDisabled", !hasName);
            }

            validate();
            $('#name').on('input change', validate);

            let tagsInput = document.querySelector('#tags');
            new Tagify(tagsInput);
        });

        mcutil.onAlpineInit("datasets_create", () => {
            return {
                setActionAndSubmit(action) {
                    $('#action').val(action);
                    document.getElementById('dataset_create').submit();
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
