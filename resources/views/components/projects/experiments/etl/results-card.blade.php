@props([
    'project',
    'experiment',
    'etlRun',
    'processResults' => collect(),
    'validationMessages' => collect(),
    'logEntries' => collect(),
    'activeTab' => 'process-results',
])

<div class="card shadow-sm border-0" style="border-radius:.85rem;">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2">
            <div>
                <h2 class="h5 mb-1">Import Results</h2>
                <div class="text-muted small">
                    Review process results, validation messages, and the import log.
                </div>
            </div>

            <div class="btn-group btn-group-sm">
                <a href="{{ route('projects.experiments.etl_run.show', [$project, $experiment, $etlRun]) }}"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-terminal me-1"></i>
                    Full Log
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs" id="etl-result-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'process-results' ? 'active' : '' }}"
                        type="button"
                        wire:click="setTab('process-results')">
                    <i class="fas fa-project-diagram me-1"></i>
                    Process Results
                    <span class="badge text-bg-secondary ms-1">{{ $processResults->count() }}</span>
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'validation' ? 'active' : '' }}"
                        type="button"
                        wire:click="setTab('validation')">
                    <i class="fas fa-clipboard-check me-1"></i>
                    Validation
                    <span class="badge text-bg-warning ms-1">
                        {{ $validationMessages->where('severity.value', 'warning')->count() }}
                    </span>
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'log' ? 'active' : '' }}"
                        type="button"
                        wire:click="setTab('log')">
                    <i class="fas fa-terminal me-1"></i>
                    Log
                </button>
            </li>
        </ul>

        <div class="pt-3">
            @if($activeTab === 'process-results')
                <x-projects.experiments.etl.process-results-table :process-results="$processResults" />
            @elseif($activeTab === 'validation')
                <x-projects.experiments.etl.validation-messages :validation-messages="$validationMessages" />
            @elseif($activeTab === 'log')
                <x-projects.experiments.etl.log-entries :log-entries="$logEntries" />
            @endif
        </div>
    </div>
</div>
