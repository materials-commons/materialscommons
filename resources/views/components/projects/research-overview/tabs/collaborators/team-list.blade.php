@props([
    'project',
    'metrics' => [],
])

@php
    $owner = $metrics['owner'] ?? null;
    $admins = collect($metrics['admins'] ?? []);
    $members = collect($metrics['members'] ?? []);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-users me-1"></i>Project Team
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Owner, admins, and members assigned to this project.
                </p>
            </div>

            <a href="{{ route('projects.users.index', [$project]) }}"
               class="btn btn-sm btn-outline-primary">
                Manage
            </a>
        </div>

        <div class="mb-3">
            <div class="text-muted small fw-semibold mb-1">Owner</div>

            @if($owner)
                <div class="border rounded p-2">
                    <div class="fw-semibold text-break">{{ $owner->name }}</div>
                    <div class="text-muted text-break" style="font-size:.78rem;">{{ $owner->email }}</div>
                </div>
            @else
                <div class="text-muted small">No owner assigned.</div>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="text-muted small fw-semibold mb-1">Admins</div>

                @forelse($admins->sortBy('name') as $admin)
                    <div class="border rounded p-2 mb-2">
                        <div class="fw-semibold text-break" style="font-size:.85rem;">{{ $admin->name }}</div>
                        <div class="text-muted text-break" style="font-size:.72rem;">{{ $admin->email }}</div>
                    </div>
                @empty
                    <div class="text-muted small">No admins listed.</div>
                @endforelse
            </div>

            <div class="col-12 col-lg-6">
                <div class="text-muted small fw-semibold mb-1">Members</div>

                @forelse($members->sortBy('name') as $member)
                    <div class="border rounded p-2 mb-2">
                        <div class="fw-semibold text-break" style="font-size:.85rem;">{{ $member->name }}</div>
                        <div class="text-muted text-break" style="font-size:.72rem;">{{ $member->email }}</div>
                    </div>
                @empty
                    <div class="text-muted small">No members listed.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
