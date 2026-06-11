@props([
    'project',
])

@php
    $members = collect($project->team?->members ?? collect());
    $admins = collect($project->team?->admins ?? collect());
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Owner"
            value="1"
            :hint="$project->owner->name"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Admins"
            :value="$admins->count()"
            hint="project admins"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Members"
            :value="$members->count()"
            hint="project members"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Recent Contributors"
            value="—"
            hint="placeholder"
            color="secondary"
        />
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
            <div>
                <h6 class="card-title text-muted">
                    <i class="fas fa-users me-1"></i>Collaborators
                </h6>
                <p class="text-muted mb-2">
                    Collaborator analytics placeholder for owner, admins, members, recent contributors,
                    inactive collaborators, and dataset authors.
                </p>
            </div>

            <a href="{{ route('projects.users.index', [$project]) }}"
               class="btn btn-sm btn-outline-primary">
                <i class="fas fa-users me-1"></i>Manage Members
            </a>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-muted">Admins</h6>
                    @forelse($admins->take(6) as $admin)
                        <div class="text-muted small">{{ $admin->name }}</div>
                    @empty
                        <div class="text-muted small">No admins listed.</div>
                    @endforelse
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-muted">Members</h6>
                    @forelse($members->take(6) as $member)
                        <div class="text-muted small">{{ $member->name }}</div>
                    @empty
                        <div class="text-muted small">No members listed.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
