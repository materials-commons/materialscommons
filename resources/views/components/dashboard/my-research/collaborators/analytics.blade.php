@props([
    'collaborators' => collect(),
    'publishedDatasetCoAuthors' => collect(),
    'projectCollaborators' => collect(),
    'teamMembers' => collect(),
    'frequentCollaborators' => collect(),
    'privateAccessUsers' => collect(),
    'externalAuthors' => collect(),
])

@php
    $collaborators = collect($collaborators);
    $publishedDatasetCoAuthors = collect($publishedDatasetCoAuthors);
    $projectCollaborators = collect($projectCollaborators);
    $teamMembers = collect($teamMembers);
    $frequentCollaborators = collect($frequentCollaborators);
    $privateAccessUsers = collect($privateAccessUsers);
    $externalAuthors = collect($externalAuthors);

    $withBothDatasetsAndProjects = $collaborators->filter(fn($collaborator) => $collaborator['dataset_count'] > 0 && $collaborator['project_count'] > 0);

    $topAffiliations = $collaborators
        ->pluck('affiliations')
        ->filter()
        ->flatMap(fn($affiliations) => collect(explode(';', $affiliations))->map(fn($affiliation) => trim($affiliation)))
        ->filter()
        ->countBy()
        ->sortDesc()
        ->take(5);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-chart-pie me-1"></i>Collaborator Analytics
        </h6>
        <p class="text-muted mb-3" style="font-size:.85rem;">
            Counts are derived from loaded projects, team access, and dataset author metadata.
        </p>

        <div class="row g-2 mb-3">
            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Co-authors from published datasets</div>
                    <div class="fw-semibold">{{ number_format($publishedDatasetCoAuthors->count()) }}</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Project collaborators</div>
                    <div class="fw-semibold">{{ number_format($projectCollaborators->count()) }}</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Team members/admins</div>
                    <div class="fw-semibold">{{ number_format($teamMembers->count()) }}</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Frequent collaborators</div>
                    <div class="fw-semibold">{{ number_format($frequentCollaborators->count()) }}</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Private project access</div>
                    <div class="fw-semibold">{{ number_format($privateAccessUsers->count()) }}</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Dataset + project overlap</div>
                    <div class="fw-semibold">{{ number_format($withBothDatasetsAndProjects->count()) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-2">
            <div class="text-muted mb-2" style="font-size:.8rem;">
                <i class="fas fa-building me-1"></i>Top affiliations
            </div>

            @forelse($topAffiliations as $affiliation => $count)
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <span class="text-truncate" title="{{ $affiliation }}">{{ $affiliation }}</span>
                    <span class="badge text-bg-light border text-muted">{{ number_format($count) }}</span>
                </div>
            @empty
                <div class="text-muted text-center py-3">
                    No affiliation metadata found.
                </div>
            @endforelse
        </div>
    </div>
</div>
