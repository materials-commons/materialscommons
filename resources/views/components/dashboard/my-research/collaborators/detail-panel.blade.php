@props([
    'frequentCollaborators' => collect(),
    'externalAuthors' => collect(),
    'privateAccessUsers' => collect(),
])

@php
    $frequentCollaborators = collect($frequentCollaborators)->sortByDesc('score')->take(5);
    $externalAuthors = collect($externalAuthors)->sortByDesc('published_dataset_count')->take(5);
    $privateAccessUsers = collect($privateAccessUsers)->sortByDesc('private_project_count')->take(5);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-user-friends me-1"></i>Collaboration Highlights
        </h6>
        <p class="text-muted mb-3" style="font-size:.85rem;">
            Quick lists for frequent, external, and private-project collaborators.
        </p>

        <div class="mb-3">
            <div class="fw-semibold mb-2" style="font-size:.85rem;">Frequent collaborators</div>

            @forelse($frequentCollaborators as $collaborator)
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <span class="text-truncate">{{ $collaborator['name'] }}</span>
                    <span class="badge text-bg-warning">{{ number_format($collaborator['score']) }} links</span>
                </div>
            @empty
                <div class="text-muted" style="font-size:.85rem;">No frequent collaborators yet.</div>
            @endforelse
        </div>

        <div class="mb-3">
            <div class="fw-semibold mb-2" style="font-size:.85rem;">External/non-MC authors</div>

            @forelse($externalAuthors as $collaborator)
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <span class="text-truncate">{{ $collaborator['name'] }}</span>
                    <span class="badge text-bg-danger">{{ number_format($collaborator['published_dataset_count']) }} datasets</span>
                </div>
            @empty
                <div class="text-muted" style="font-size:.85rem;">No external dataset authors found.</div>
            @endforelse
        </div>

        <div>
            <div class="fw-semibold mb-2" style="font-size:.85rem;">Users with private project access</div>

            @forelse($privateAccessUsers as $collaborator)
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <span class="text-truncate">{{ $collaborator['name'] }}</span>
                    <span class="badge text-bg-info">{{ number_format($collaborator['private_project_count']) }} projects</span>
                </div>
            @empty
                <div class="text-muted" style="font-size:.85rem;">No private project access users found.</div>
            @endforelse
        </div>
    </div>
</div>
