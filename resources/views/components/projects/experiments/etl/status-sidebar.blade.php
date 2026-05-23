@props([
    'etlRun',
    'steps' => collect(),
    'metrics' => [],
])

<div class="card shadow-sm border-0" style="border-radius:.85rem; position:sticky; top:64px;">
    <div class="card-header bg-white">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h2 class="h5 mb-1">Import Status</h2>
                <div class="text-muted small">
                    Started:
                    {{ $etlRun->started_at?->format('M j, Y g:i A') ?? 'Waiting for worker' }}
                </div>
            </div>

            <span class="badge {{ $etlRun->status?->badgeClass() ?? 'text-bg-secondary' }}">
                {{ $etlRun->status?->label() ?? 'Queued' }}
            </span>
        </div>
    </div>

    <div class="card-body">
        <x-projects.experiments.etl.progress-bar :etl-run="$etlRun" />

        <div class="p-3 rounded-3 mb-3" style="background:#f8f9fa;">
            <div class="fw-semibold mb-1">
                <i class="fas fa-circle-notch me-1 {{ $etlRun->isActive() ? 'fa-spin text-primary' : 'text-muted' }}"></i>
                {{ $etlRun->current_step ? str_replace('_', ' ', ucfirst($etlRun->current_step)) : 'Queued' }}
            </div>
            <div class="text-muted small">
                {{ $etlRun->current_message ?? 'Waiting for import status.' }}
            </div>
        </div>

        <x-projects.experiments.etl.summary-metrics :metrics="$metrics" />

        <hr>

        <x-projects.experiments.etl.step-list :steps="$steps" />

        @if($etlRun->finished_at)
            <div class="text-muted small mt-3">
                Finished: {{ $etlRun->finished_at->format('M j, Y g:i A') }}
            </div>
        @endif
    </div>
</div>
