@props([
    'project',
])

@php
    $membersCount = collect($project->team?->members ?? collect())->count();
    $adminsCount = collect($project->team?->admins ?? collect())->count();

    $healthLabel = match ($project->health) {
        'critical' => 'Critical',
        'warning' => 'Warning',
        null => 'Unknown',
        default => 'Healthy',
    };

    $healthColor = match ($project->health) {
        'critical' => 'danger',
        'warning' => 'warning',
        null => 'secondary',
        default => 'success',
    };
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 background-white">
        <div class="d-flex gap-4 align-items-start">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-light border"
                 style="width:80px; height:80px;">
                <i class="fas fa-folder-open fa-2x text-muted"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                    <div>
                        <h4 class="mb-1">{{ $project->name }}</h4>
                        <p class="text-muted mb-2" style="font-size:.9rem;">
                            A private project overview of files, studies, datasets, samples, processes,
                            collaborators, health, and metadata readiness.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-folder-open me-1"></i>Browse Files
                        </a>

                        <a href="{{ route('projects.upload-files', [$project]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-upload me-1"></i>Upload
                        </a>

                        <a href="{{ route('projects.experiments.create', [$project]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-flask me-1"></i>New Study
                        </a>

                        <a href="{{ route('projects.datasets.create', [$project]) }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="fas fa-database me-1"></i>New Dataset
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <span class="text-muted">
                        <i class="fas fa-user me-1" style="font-size:.8rem;"></i>
                        Owner: {{ $project->owner->name }}
                    </span>

                    <span class="text-muted">
                        <i class="fas fa-users me-1" style="font-size:.8rem;"></i>
                        {{ $membersCount }} member(s), {{ $adminsCount }} admin(s)
                    </span>

                    <span class="text-muted">
                        <i class="far fa-clock me-1" style="font-size:.8rem;"></i>
                        Updated {{ $project->updated_at->diffForHumans() }}
                    </span>

                    <span class="text-muted">
                        <i class="fas fa-hdd me-1" style="font-size:.8rem;"></i>
                        {{ formatBytes($project->size) }}
                    </span>

                    <span class="badge text-bg-{{ $healthColor }}">
                        <i class="fas fa-heartbeat me-1"></i>{{ $healthLabel }}
                    </span>

                    @if(auth()->user()->isActiveProject($project))
                        <span class="badge text-bg-warning">
                            <i class="fas fa-star me-1"></i>Active Project
                        </span>
                    @endif
                </div>

                @if(filled($project->description ?? null))
                    <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">
                        {{ $project->description }}
                    </p>
                @elseif(filled($project->summary ?? null))
                    <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">
                        {{ $project->summary }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
