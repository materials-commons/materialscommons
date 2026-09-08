<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2 px-3 background-white">
        <div class="d-flex flex-wrap gap-3 align-items-center" style="font-size:.82rem;">

            <span class="text-muted">
                <i class="fas fa-user fa-fw me-1"></i>
                <strong>Owner:</strong> {{ $project->owner->name }}
            </span>

            <span class="text-muted">
                <i class="fas fa-users fa-fw me-1"></i>
                <a href="{{route('projects.users.index', [$project])}}" class="text-muted text-decoration-none">
                    {{ $project->team->members->count() }} member(s),
                    {{ $project->team->admins->count() }} admin(s)
                </a>
            </span>

            <span class="text-muted"
                  data-bs-toggle="tooltip"
                  title="{{ $project->updated_at->format('M j, Y g:i a') }}">
                <i class="far fa-clock fa-fw me-1"></i>
                <strong>Updated:</strong> {{ $project->updated_at->diffForHumans() }}
            </span>

            <span class="text-muted">
                <i class="fas fa-hdd fa-fw me-1"></i>
                <strong>Size:</strong> {{ formatBytes($project->size) }}
            </span>

            <span class="text-muted">
                <i class="fas fa-tag fa-fw me-1"></i>
                <strong>Slug:</strong> <code class="text-muted">{{ $project->slug }}</code>
            </span>

            <span class="text-muted">
                <i class="fas fa-fingerprint fa-fw me-1"></i>
                <strong>ID:</strong> {{ $project->id }}
            </span>

        </div>

        {{-- Description / summary if present --}}
        @if(isset($project->description) && !blank($project->description))
            <hr class="my-2">
            <p class="mb-0 text-muted" style="font-size:.85rem;">{{ $project->description }}</p>
        @elseif(isset($project->summary) && !blank($project->summary))
            <hr class="my-2">
            <p class="mb-0 text-muted" style="font-size:.85rem;">{{ $project->summary }}</p>
        @endif
    </div>
</div>
