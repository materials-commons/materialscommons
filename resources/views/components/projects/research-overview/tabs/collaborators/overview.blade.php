@props([
    'project',
    'metrics' => [],
])

@php
    $readinessPercent = (int) ($metrics['readinessPercent'] ?? 0);
    $needsReviewCount = collect($metrics['needsReview'] ?? [])->count();

    $readinessColor = match (true) {
        $readinessPercent >= 80 => 'success',
        $readinessPercent >= 60 => 'info',
        $readinessPercent >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-users me-1"></i>Collaborators Overview
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Team readiness based on owner, admins, members, and dataset author metadata.
                </p>
            </div>

            <span class="badge text-bg-{{ $readinessColor }}">
                {{ $readinessPercent }}% ready
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $readinessColor }}"
                 role="progressbar"
                 style="width: {{ $readinessPercent }}%;"
                 aria-valuenow="{{ $readinessPercent }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Team Users</div>
                    <div class="fw-bold text-primary">{{ number_format((int) ($metrics['teamUserCount'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Dataset Authors</div>
                    <div class="fw-bold text-success">{{ number_format((int) ($metrics['datasetAuthorCount'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">External Authors</div>
                    <div class="fw-bold text-info">{{ number_format((int) ($metrics['externalDatasetAuthorCount'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Admins</div>
                    <div class="fw-bold text-warning">{{ number_format((int) ($metrics['adminCount'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Members</div>
                    <div class="fw-bold text-primary">{{ number_format((int) ($metrics['memberCount'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Needs Review</div>
                    <div class="fw-bold text-warning">{{ number_format($needsReviewCount) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-3 bg-light">
            <div class="fw-semibold small mb-1">
                <i class="fas fa-lightbulb text-warning me-1"></i>Recommended Collaborator Action
            </div>

            @if(($metrics['ownerCount'] ?? 0) === 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project does not have an owner assigned. Verify project ownership.
                </p>
            @elseif($needsReviewCount > 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some team or dataset author metadata needs review, especially missing dataset authors.
                </p>
            @else
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Collaborator readiness looks good. Keep dataset authors current as datasets are published.
                </p>
            @endif

            <a href="{{ route('projects.users.index', [$project]) }}"
               class="btn btn-sm btn-outline-primary">
                <i class="fas fa-users me-1"></i>Manage Members
            </a>
        </div>
    </div>
</div>
