<x-inner-card>
    <x-slot:title>Create Study</x-slot:title>
    <x-slot:body>
        <div class="mb-3 mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text"
                   value="{{old('name')}}"
                   placeholder="Name...">
        </div>
        <div class="mb-3 mb-3">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" name="summary" type="text"
                   value="{{old('summary')}}"
                   placeholder="Summary...">
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
                      placeholder="Description...">{{old('description')}}</textarea>
        </div>
        <div class="float-end">
            <a href="{{route('projects.show', ['project' => $project->id])}}"
               class="btn btn-danger danger me-3">
                Cancel
            </a>

            <a class="btn btn-success me-3" href="#"
               onclick="document.getElementById('experiment-create').submit()">
                Create
            </a>
        </div>
    </x-slot:body>
</x-inner-card>
