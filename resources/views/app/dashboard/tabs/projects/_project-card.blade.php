@php
    $recentlyAccessedOn = auth()->user()->projectRecentlyAccessedOn($proj);
@endphp
<div class="active-project-item">
    <div class="project-main">
        <div class="project-info">
            <div class="project-title">
                <a href="{{route('projects.show', [$proj])}}" class="text-decoration-none">
                    <x-health.projects.health-status-badge-small :project="$proj"/>
                    {{$proj->name}}
                </a>
            </div>
            <div class="project-meta">
                <span class="text-muted"><i class="far fa-clock"></i>{{Carbon\Carbon::parse($recentlyAccessedOn)->diffForHumans()}}</span>
                <span class="text-muted"><i class="fas fa-file ml-3"></i>{{number_format($proj->file_count)}}</span>
                <span class="text-muted"><i class="fas fa-hdd ml-3"></i>{{formatBytes($proj->size)}}</span>
            </div>
        </div>
        <div class="project-actions">
            <a href="{{route('projects.folders.index', [$proj])}}"
               class="btn btn-sm btn-light" title="Files">
                <i class="fas fa-folder"></i>
            </a>
            <a href="{{route('projects.experiments.index', [$proj])}}"
               class="btn btn-sm btn-light" title="Studies">
                <i class="fas fa-flask"></i>
            </a>
            <a href="{{route('dashboard.projects.unmark-as-active', [$proj])}}"
               class="btn btn-sm btn-light" title="Remove from active">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
</div>
