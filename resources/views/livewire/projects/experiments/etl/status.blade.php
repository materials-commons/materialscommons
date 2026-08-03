<div @if($this->shouldPoll) wire:poll.1500ms="refreshRun" @endif>
    <div class="container-fluid py-3">
        <div class="d-flex flex-column flex-xl-row align-items-start justify-content-between gap-3 mb-3">
            <div>
                <div class="text-muted small text-uppercase fw-semibold">Spreadsheet Import</div>
                <h1 class="h3 mb-1">{{ $experiment->name }}</h1>
                <div class="text-muted">
                    {{ $etlRun->source_name ?? 'Spreadsheet import' }}
                    @if(!blank($etlRun->source_type))
                        <span class="mx-1">&middot;</span>
                        {{ str_replace('_', ' ', $etlRun->source_type) }}
                    @endif
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.experiments.show', [$project, $experiment]) }}"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Study
                </a>

                <a href="{{ route('projects.experiments.etl_run.show', [$project, $experiment, $etlRun]) }}"
                   class="btn btn-outline-primary">
                    <i class="fas fa-file-alt me-1"></i>
                    Classic Log View
                </a>
            </div>
        </div>

        @if($etlRun->status?->value === 'completed')
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>
                Spreadsheet import completed successfully.
            </div>
        @elseif($etlRun->status?->value === 'failed')
            <div class="alert alert-danger">
                <div class="fw-semibold">
                    <i class="fas fa-times-circle me-1"></i>
                    Spreadsheet import failed.
                </div>
                @if(!blank($etlRun->error_message))
                    <div class="small mt-1">{{ $etlRun->error_message }}</div>
                @endif
            </div>
        @elseif($etlRun->status?->value === 'cancelled')
            <div class="alert alert-warning">
                <i class="fas fa-ban me-1"></i>
                Spreadsheet import was cancelled.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-xl-4">
                <x-projects.experiments.etl.status-sidebar
                    :etl-run="$etlRun"
                    :steps="$etlRun->steps"
                    :metrics="$this->metrics"
                />
            </div>

            <div class="col-xl-8">
                <x-projects.experiments.etl.results-card
                    :project="$project"
                    :experiment="$experiment"
                    :etl-run="$etlRun"
                    :process-results="$etlRun->processResults"
                    :validation-messages="$etlRun->validationMessages"
                    :log-entries="$etlRun->logEntries"
                    :active-tab="$tab"
                />
            </div>
        </div>
    </div>
</div>
