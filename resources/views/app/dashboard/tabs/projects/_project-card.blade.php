@php
    $recentlyAccessedOn = auth()->user()->projectRecentlyAccessedOn($proj);
@endphp
<div class="list-group-item px-0 py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="flex-grow-1">
            <div class="fw-semibold mb-1">
                <a href="{{route('projects.show', [$proj])}}" class="text-decoration-none">
                    <x-health.projects.health-status-badge-small :project="$proj"/>
                    {{$proj->name}}
                </a>
            </div>
            <div class="d-flex flex-wrap gap-3 text-muted small">
                <span><i class="far fa-clock me-1"></i>{{Carbon\Carbon::parse($recentlyAccessedOn)->diffForHumans()}}</span>
                <span><i class="fas fa-file me-1"></i>{{number_format($proj->file_count)}}</span>
                <span><i class="fas fa-hdd me-1"></i>{{formatBytes($proj->size)}}</span>
            </div>
        </div>
        <div class="btn-group btn-group-sm">
            <a href="{{route('projects.folders.index', [$proj])}}"
               class="btn btn-light" title="Files">
                <i class="fas fa-folder"></i>
            </a>
            <a href="{{route('projects.experiments.index', [$proj])}}"
               class="btn btn-light" title="Studies">
                <i class="fas fa-flask"></i>
            </a>
            <a href="{{route('dashboard.projects.unmark-as-active', [$proj])}}"
               class="btn btn-light" title="Remove from active">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
</div>
