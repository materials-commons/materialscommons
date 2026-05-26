@props([
    'project' => null,
    'focusedProjectId' => null,
    'focusedProjectName' => null,
    'visibleLeafCount' => 0,
])

<div class="mc-browser-toolbar">
    <div class="row g-2 align-items-center">
        <div class="col-xl-5 col-lg-6">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="fas fa-search text-muted"></i>
                </span>

                <input type="search"
                       class="form-control"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Filter loaded tree...">

                <button type="button" class="btn btn-outline-secondary" wire:click="clearSearch">
                    Clear
                </button>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4">
            <select class="form-select" wire:model.live="scope">
                @if($project !== null || $focusedProjectId !== null)
                    <option value="project">
                        Current project
                        @if($project === null && $focusedProjectName !== null)
                            — {{ $focusedProjectName }}
                        @endif
                    </option>
                @endif
                <option value="all">All projects</option>
            </select>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4">
            <select class="form-select" wire:model.live="groupBy">
                <option value="project">Group by project</option>
                <option value="type">Group by data type</option>
            </select>
        </div>

        <div class="col-xl-3 col-lg-12">
            <div class="text-xl-end text-muted small">
                {{ $visibleLeafCount }} {{ Illuminate\Support\Str::plural('visible item', $visibleLeafCount) }}
            </div>
        </div>
    </div>
</div>
