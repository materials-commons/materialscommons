@props([
    'projects' => collect(),
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-folder-open me-1"></i>All Projects
        </h6>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Project</th>
                    <th>Files</th>
                    <th>Size</th>
                    <th>Members</th>
                    <th>Datasets</th>
                    <th>Last Activity</th>
                    <th>Metadata</th>
                </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                    @php
                        $membersCount = optional($project->team?->members)->count() + optional($project->team?->admins)->count();

                        $metadataFields = [
                            !blank($project->name ?? null),
                            !blank($project->description ?? null),
                            (int) ($project->file_count ?? 0) > 0,
                            (int) ($project->published_datasets_count ?? 0) > 0 || (int) ($project->unpublished_datasets_count ?? 0) > 0,
                            $membersCount > 0,
                        ];

                        $metadataCompleteness = round(collect($metadataFields)->filter()->count() / count($metadataFields) * 100);
                    @endphp

                    <tr>
                        <td>
                            <a href="{{ route('projects.show', [$project->id]) }}" class="text-decoration-none fw-semibold">
                                {{ $project->name }}
                            </a>
                            @if(!blank($project->description ?? null))
                                <div class="text-muted text-truncate" style="max-width:240px; font-size:.75rem;">
                                    {{ $project->description }}
                                </div>
                            @endif
                        </td>
                        <td>{{ number_format($project->file_count ?? 0) }}</td>
                        <td>{{ formatBytes($project->size ?? 0) }}</td>
                        <td>{{ number_format($membersCount) }}</td>
                        <td>
                            <span class="badge text-bg-success">
                                {{ number_format($project->published_datasets_count ?? 0) }} published
                            </span>
                            <span class="badge text-bg-warning">
                                {{ number_format($project->unpublished_datasets_count ?? 0) }} draft
                            </span>
                        </td>
                        <td>
                            <span title="{{ optional($project->updated_at)->format('M j, Y g:i A') }}">
                                {{ optional($project->updated_at)->diffForHumans() }}
                            </span>
                        </td>
                        <td style="min-width:120px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-success"
                                         role="progressbar"
                                         style="width: {{ $metadataCompleteness }}%;"
                                         aria-valuenow="{{ $metadataCompleteness }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size:.75rem;">{{ $metadataCompleteness }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center py-4">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
