@props([
    'project',
    'experiment',
    'etlRun',
    'processResults' => collect(),
    'validationMessages' => collect(),
    'logEntries' => collect(),
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
                <button class="nav-link active"
                        id="process-results-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#process-results"
                        type="button"
                        role="tab">
                    <i class="fas fa-project-diagram me-1"></i>
                    Process Results
                    <span class="badge text-bg-secondary ms-1">{{ $processResults->count() }}</span>
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="validation-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#validation"
                        type="button"
                        role="tab">
                    <i class="fas fa-clipboard-check me-1"></i>
                    Validation
                    <span class="badge text-bg-warning ms-1">
                        {{ $validationMessages->where('severity.value', 'warning')->count() }}
                    </span>
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="log-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#log"
                        type="button"
                        role="tab">
                    <i class="fas fa-terminal me-1"></i>
                    Log
                </button>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="process-results" role="tabpanel">
                <x-projects.experiments.etl.process-results-table :process-results="$processResults" />
            </div>

            <div class="tab-pane fade" id="validation" role="tabpanel">
                <x-projects.experiments.etl.validation-messages :validation-messages="$validationMessages" />
            </div>

            <div class="tab-pane fade" id="log" role="tabpanel">
                <x-projects.experiments.etl.log-entries :log-entries="$logEntries" />
            </div>
        </div>
    </div>
</div>
