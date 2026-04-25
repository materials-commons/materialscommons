{{--
  Prototype v2 project Home tab.
  To try it: in show.blade.php change
      @include('app.projects.tabs.home')
  to
      @include('app.projects.tabs.home-v2')

  Layout (top to bottom):
    1. KPI stat cards         — always visible, at-a-glance project numbers
    2. ▶ Analytics            — collapsible, default CLOSED  (3 Plotly charts)
    3. ▶ Quick Actions        — collapsible, default OPEN    (action buttons)
    4. ▶ Getting Started      — collapsible, default CLOSED  (original 3 feature cards)
    5. README                 — always visible               (existing component)

  All collapse states are persisted in localStorage per-project so preferences
  stick across page loads.  Keys use the project id so different projects can
  have different preferences.
--}}

@php
    $projKey = 'mc_proj_' . $project->id;
@endphp

{{-- ══════════════════════════════════════════════════════════════════════════
     1. KPI STAT CARDS — always visible
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.folders.show', [$project, $project->rootDir])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2">
                    <div class="text-muted small mb-1">Files</div>
                    <div class="fw-bold fs-5 text-primary">{{number_format($project->file_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{number_format($project->directory_count)}} dirs
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.experiments.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2">
                    <div class="text-muted small mb-1">Studies</div>
                    <div class="fw-bold fs-5 text-info">{{number_format($project->experiments_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">experimental</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.entities.index', [$project, 'category' => 'experimental'])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2">
                    <div class="text-muted small mb-1">Samples</div>
                    <div class="fw-bold fs-5 text-teal">{{number_format($project->entities_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">tracked</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.datasets.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2">
                    <div class="text-muted small mb-1">Datasets</div>
                    <div class="fw-bold fs-5 text-success">{{number_format($project->published_datasets_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{number_format($project->unpublished_datasets_count)}} unpublished
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="text-muted small mb-1">Storage</div>
                <div class="fw-bold fs-5 text-secondary">{{formatBytes($project->size)}}</div>
                <div class="text-muted" style="font-size:.65rem;">total used</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.users.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2">
                    <div class="text-muted small mb-1">Members</div>
                    <div class="fw-bold fs-5 text-primary">
                        {{$project->team->members->count()}}
                    </div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{$project->team->admins->count()}} admin(s)
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     2. ANALYTICS — collapsible, default CLOSED
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-analytics-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-analytics"
            aria-expanded="false"
            aria-controls="proj-analytics">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>
<div class="collapse mb-1" id="proj-analytics"
     data-mc-collapse-key="{{$projKey}}_analytics">
    @include('app.projects.tabs.home._project-charts')
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     3. QUICK ACTIONS — collapsible, default OPEN
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-actions-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-actions"
            aria-expanded="true"
            aria-controls="proj-actions">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem; transform:rotate(90deg);"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Quick Actions
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>
<div class="collapse show mb-1" id="proj-actions"
     data-mc-collapse-key="{{$projKey}}_actions"
     data-mc-collapse-default="open">
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="row g-3">

                {{-- Files column --}}
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-folder text-primary me-2"></i>
                        <strong class="small">Files</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{route('projects.folders.show', [$project, $project->rootDir])}}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-folder-open me-1"></i> Browse
                        </a>
                        <a href="{{route('projects.upload-files', [$project])}}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-upload me-1"></i> Upload
                        </a>
                        <a href="{{route('projects.globus.uploads.index', [$project])}}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-exchange-alt me-1"></i> Globus
                        </a>
                    </div>
                </div>

                {{-- Studies column --}}
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-flask text-info me-2"></i>
                        <strong class="small">Studies</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{route('projects.experiments.index', [$project])}}"
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-list me-1"></i> View All
                        </a>
                        <a href="{{route('projects.experiments.create', [$project])}}"
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-plus me-1"></i> New Study
                        </a>
                    </div>
                </div>

                {{-- Datasets column --}}
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-database text-success me-2"></i>
                        <strong class="small">Datasets</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{route('projects.datasets.index', [$project])}}"
                           class="btn btn-outline-success btn-sm">
                            <i class="fas fa-list me-1"></i> View All
                        </a>
                        <a href="{{route('projects.datasets.create', [$project])}}"
                           class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus me-1"></i> New Dataset
                        </a>
                        <a href="{{route('projects.datasets.create', [$project])}}"
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-export me-1"></i> Publish
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     4. GETTING STARTED / DOCUMENTATION — collapsible, default CLOSED
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-docs-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-docs"
            aria-expanded="false"
            aria-controls="proj-docs">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Getting Started
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>
<div class="collapse mb-3" id="proj-docs"
     data-mc-collapse-key="{{$projKey}}_docs">
    {{-- The original three feature cards, unchanged --}}
    <div class="row align-items-stretch">
        <div class="col-md-4 d-flex">
            @include('app.projects.tabs.home._files-card')
        </div>
        <div class="col-md-4 d-flex">
            @include('app.projects.tabs.home._studies-card')
        </div>
        <div class="col-md-4 d-flex">
            @include('app.projects.tabs.home._datasets-card')
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     5. README — always visible
     ══════════════════════════════════════════════════════════════════════════ --}}
<x-display-markdown-file :file="$readme"></x-display-markdown-file>

{{-- ══════════════════════════════════════════════════════════════════════════
     Collapse state management (localStorage, per-project)
     ══════════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    // Generic helper: wire up a collapse panel to localStorage.
    // defaultOpen: true = panel starts open when no preference is saved yet.
    function wireCollapse(panelId, toggleId, storageKey, defaultOpen) {
        const panel   = document.getElementById(panelId);
        const toggle  = document.getElementById(toggleId);
        if (!panel || !toggle) return;

        // Find the chevron inside this specific toggle button
        const chevron = toggle.querySelector('.proj-chevron');
        const open    = (v) => {
            chevron.style.transform = 'rotate(90deg)';
            toggle.setAttribute('aria-expanded', 'true');
        };
        const close   = (v) => {
            chevron.style.transform = 'rotate(0deg)';
            toggle.setAttribute('aria-expanded', 'false');
        };

        // Restore saved preference (or fall back to default)
        const saved = localStorage.getItem(storageKey);
        const shouldOpen = saved !== null ? saved === 'true' : defaultOpen;
        if (shouldOpen) {
            panel.classList.add('show');
            open();
        } else {
            panel.classList.remove('show');
            close();
        }

        // Persist on Bootstrap collapse events
        panel.addEventListener('show.bs.collapse', () => {
            open();
            localStorage.setItem(storageKey, 'true');
        });
        panel.addEventListener('hide.bs.collapse', () => {
            close();
            localStorage.setItem(storageKey, 'false');
        });
    }

    wireCollapse('proj-analytics', 'proj-analytics-toggle', '{{$projKey}}_analytics', false);
    wireCollapse('proj-actions',   'proj-actions-toggle',   '{{$projKey}}_actions',   true);
    wireCollapse('proj-docs',      'proj-docs-toggle',      '{{$projKey}}_docs',      false);
})();
</script>

<style>
    /* Subtle hover lift on the clickable KPI cards */
    .card-hover { transition: transform .15s, box-shadow .15s; cursor: pointer; }
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 .25rem .75rem rgba(0,0,0,.1) !important; }
</style>
@endpush
