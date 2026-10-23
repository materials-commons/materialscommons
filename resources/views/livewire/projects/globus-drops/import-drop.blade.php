<div class="card">
    <div class="card-body">
        <div class="mb-4">
            <h5 class="card-title mb-3">Drop Details</h5>

            <dl class="row mb-0">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $drop->name }}</dd>

                <dt class="col-sm-3">Globus URL</dt>
                <dd class="col-sm-9">
                    @if($drop->globus_url)
                        <a href="{{ $drop->globus_url }}" target="_blank" rel="noopener noreferrer">
                            Open Globus Drop
                        </a>
                    @else
                        <span class="text-muted">No Globus URL available</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Globus Path</dt>
                <dd class="col-sm-9">{{ $drop->globus_path }}</dd>

                <dt class="col-sm-3">Users</dt>
                <dd class="col-sm-9">
                    @if(!empty($drop->globus_accounts))
                        {{ implode(', ', $drop->globus_accounts) }}
                    @else
                        <span class="text-muted">No users</span>
                    @endif
                </dd>
            </dl>
        </div>

        @error('drop')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form wire:submit.prevent="import">
            <div class="mb-3">
                <label class="form-label">Import Into</label>

                <div class="form-check">
                    <input type="radio"
                           id="destination-existing"
                           class="form-check-input"
                           value="existing"
                           wire:model.live="destinationMode">

                    <label for="destination-existing" class="form-check-label">
                        Existing project directory
                    </label>
                </div>

                <div class="form-check">
                    <input type="radio"
                           id="destination-new"
                           class="form-check-input"
                           value="new"
                           wire:model.live="destinationMode">

                    <label for="destination-new" class="form-check-label">
                        Create or use directory path
                    </label>
                </div>
            </div>

            @if($destinationMode === 'existing')
                <div class="mb-3">
                    <label for="directoryId" class="form-label">
                        Project Directory
                        <span class="text-danger">*</span>
                    </label>

                    <select id="directoryId"
                            class="form-select @error('directoryId') is-invalid @enderror"
                            wire:model="directoryId">
                        @foreach($directories as $directory)
                            <option value="{{ $directory->id }}">
                                {{ $directory->path }}
                            </option>
                        @endforeach
                    </select>

                    @error('directoryId')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if($destinationMode === 'new')
                <div class="mb-3">
                    <label for="directoryPath" class="form-label">
                        Directory Path
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           id="directoryPath"
                           class="form-control @error('directoryPath') is-invalid @enderror"
                           wire:model.blur="directoryPath"
                           placeholder="/incoming/drop-import">

                    @error('directoryPath')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        Project paths start with <code>/</code>. If the path does not exist, it will be created.
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <a href="{{ route('projects.globus.drops.index', [$project]) }}"
                   class="btn btn-secondary me-2">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="import">
                    <span wire:loading.remove wire:target="import">
                        <i class="fas fa-file-import me-1"></i> Import Drop
                    </span>

                    <span wire:loading wire:target="import">
                        <i class="fas fa-spinner fa-spin me-1"></i> Importing...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
