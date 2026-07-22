@props([
    'project',
])

<div class="mc-study-panel h-100">
    <div class="mc-study-panel-header">
        <div>
            <h2 class="h5 mb-1">Study Details</h2>
            <div class="text-muted small">
                Basic information used to identify and describe this study.
            </div>
        </div>

        <span class="badge text-bg-primary">Required</span>
    </div>

    <div class="mc-study-panel-body">
        <div class="mb-3">
            <label for="name" class="form-label">Study Name</label>
            <input class="form-control"
                   id="name"
                   name="name"
                   type="text"
                   value="{{ old('name') }}"
                   placeholder="Name...">
        </div>

        <div class="mb-3">
            <label for="summary" class="form-label">Summary</label>
            <input class="form-control"
                   id="summary"
                   name="summary"
                   type="text"
                   value="{{ old('summary') }}"
                   placeholder="Summary...">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"
                      id="description"
                      name="description"
                      placeholder="Description...">{{ old('description') }}</textarea>
        </div>

        <div class="mc-study-create-actions">
            <a href="{{ route('projects.show', ['project' => $project->id]) }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>
                Create Study
            </button>
        </div>
    </div>
</div>
