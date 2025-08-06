<div class="w-100">
    <!-- Display Mode -->
    @if (!$isEditing)
        <div class="position-relativex">
            <div class="d-flex justify-content-end mb-2">
                <a wire:click.prevent="toggleEdit" class="btn btn-sm btn-outline-primary me-2" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a wire:click.prevent="save" class="btn btn-sm btn-outline-success" title="Save">
                    <i class="fas fa-save"></i> Save
                </a>
            </div>
            <x-markdown class="w-100">{!! $content !!}</x-markdown>
        </div>
    @else
        <!-- Edit Mode -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fs-4 fw-medium">Edit Markdown</h3>
                <div>
                    <a wire:click.prevent="togglePreview"
                        class="btn btn-sm {{ $showPreview ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        {{ $showPreview ? 'Hide Preview' : 'Show Preview' }}
                    </a>
                </div>
            </div>

            <div class="{{ $showPreview ? 'row g-4' : '' }}">
                <!-- Editor -->
                <div class="{{ $showPreview ? 'col-md-6' : 'w-100' }}">
                    <textarea
                        wire:model.live="editContent"
                        class="form-control"
                        style="height: 250px;"
                        placeholder="Enter markdown content here..."
                    ></textarea>
                </div>

                <!-- Preview Panel -->
                @if ($showPreview)
                    <div class="col-md-6">
                        <div class="border rounded p-4 overflow-auto bg-light" style="height: 250px;">
                            <h4 class="fs-6 fw-medium text-secondary mb-2">Preview</h4>
                            <div class="w-100">
                                <x-markdown>{!! $editContent !!}</x-markdown>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end mt-3">
                <a wire:click.prevent="cancel" class="btn btn-outline-secondary me-2">
                    Cancel
                </a>
                <a wire:click.prevent="save" class="btn btn-primary">
                    Save
                </a>
            </div>
        </div>
    @endif

</div>
