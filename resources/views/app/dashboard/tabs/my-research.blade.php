@php
    $user = auth()->user();
    $analyticsKey = 'mc_dashboard_my_research_analytics';
@endphp

{{-- ══ Private profile / research header ═════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 background-white">
        <div class="d-flex gap-4 align-items-start">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-light border"
                 style="width:80px; height:80px;">
                <i class="fas fa-user fa-2x text-muted"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                    <div>
                        <h4 class="mb-1">My Research Overview</h4>
                        <p class="text-muted mb-2" style="font-size:.9rem;">
                            A private overview of your projects, datasets, publications, licenses, collaborators,
                            and metadata readiness.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dashboard.projects.show') }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-folder-open me-1"></i>Projects
                        </a>
                        <a href="{{ route('dashboard.published-datasets.show') }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-database me-1"></i>Published Datasets
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <span class="text-muted">
                        <i class="fas fa-user me-1" style="font-size:.8rem;"></i>{{ $user->name }}
                    </span>

                    @if(!blank($user->affiliations ?? null))
                        <span class="text-muted">
                            <i class="fas fa-building me-1" style="font-size:.8rem;"></i>{{ $user->affiliations }}
                        </span>
                    @endif

                    @if(!blank($user->orcid ?? null))
                        <span class="text-muted">
                            <i class="fas fa-id-badge me-1" style="font-size:.8rem;"></i>ORCID: {{ $user->orcid }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ KPI strip — placeholders for now ══════════════════════════════════════════════ --}}
<x-dashboard.my-research.kpi/>

{{-- ══ Needs attention placeholder ═══════════════════════════════════════════════════ --}}
<x-dashboard.my-research.needs-attention/>

{{-- ══ Analytics — collapsible placeholder ═══════════════════════════════════════════ --}}
<x-dashboard.my-research.analytics.overview
    :projects="$projects ?? collect()"
    :active-projects="$activeProjects ?? collect()"
    :recently-accessed-projects="$recentlyAccessedProjects ?? collect()"
    :archived-projects="$archivedProjects ?? collect()"
    :deleted-projects="$deletedProjects ?? collect()"
    :datasets="$datasets ?? collect()"
/>

{{-- ══ Content tabs ══════════════════════════════════════════════════════════════════ --}}
<ul class="nav nav-pills mb-3" id="my-research-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active"
                id="my-research-overview-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-overview"
                type="button"
                role="tab"
                aria-controls="tab-my-research-overview"
                aria-selected="true">
            <i class="fas fa-home me-1"></i>Overview
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-projects-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-projects"
                type="button"
                role="tab"
                aria-controls="tab-my-research-projects"
                aria-selected="false">
            <i class="fas fa-folder-open me-1"></i>Projects
            <span class="badge text-bg-primary ms-1">{{$projectsCount}}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-datasets-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-datasets"
                type="button"
                role="tab"
                aria-controls="tab-my-research-datasets"
                aria-selected="false">
            <i class="fas fa-database me-1"></i>Datasets
            <span class="badge text-bg-info ms-1">{{ $datasets->count() }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-licenses-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-licenses"
                type="button"
                role="tab"
                aria-controls="tab-my-research-licenses"
                aria-selected="false">
            <i class="fas fa-balance-scale me-1"></i>Licenses
{{--            <span class="badge text-bg-danger ms-1">—</span>--}}
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-publications-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-publications"
                type="button"
                role="tab"
                aria-controls="tab-my-research-publications"
                aria-selected="false">
            <i class="fas fa-file-alt me-1"></i>Publications
            <span class="badge text-bg-secondary ms-1">—</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-collaborators-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-collaborators"
                type="button"
                role="tab"
                aria-controls="tab-my-research-collaborators"
                aria-selected="false">
            <i class="fas fa-users me-1"></i>Collaborators
            <span class="badge text-bg-warning ms-1">—</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="my-research-metadata-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-my-research-metadata"
                type="button"
                role="tab"
                aria-controls="tab-my-research-metadata"
                aria-selected="false">
            <i class="fas fa-clipboard-check me-1"></i>Metadata
            <span class="badge text-bg-success ms-1">—</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="my-research-tabs-content">
    {{-- ── Overview ─────────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade show active"
         id="tab-my-research-overview"
         role="tabpanel"
         aria-labelledby="my-research-overview-tab">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <x-dashboard.my-research.research-summary />
            </div>

            <div class="col-12 col-lg-6">
                <x-dashboard.my-research.recommended-actions />
            </div>
        </div>
    </div>

    {{-- ── Projects ─────────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-projects"
         role="tabpanel"
         aria-labelledby="my-research-projects-tab">
        <x-dashboard.my-research.projects.overview
            :projects="$projects ?? collect()"
            :active-projects="$activeProjects ?? collect()"
            :recently-accessed-projects="$recentlyAccessedProjects ?? collect()"
            :archived-projects="$archivedProjects ?? collect()"
            :deleted-projects="$deletedProjects ?? collect()"
        />
    </div>

    {{-- ── Datasets ─────────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-datasets"
         role="tabpanel"
         aria-labelledby="my-research-datasets-tab">
        <x-dashboard.my-research.datasets.overview
            :datasets="$datasets ?? collect()"
            :projects="$projects ?? collect()"/>
    </div>

    {{-- ── Licenses ─────────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-licenses"
         role="tabpanel"
         aria-labelledby="my-research-licenses-tab">
        <x-dashboard.my-research.licenses.overview
            :datasets="$datasets ?? collect()"
            :projects="$projects ?? collect()"/>
{{--        <div class="row g-3">--}}
{{--            <div class="col-12 col-lg-5">--}}
{{--                <div class="card border-0 shadow-sm h-100">--}}
{{--                    <div class="card-body p-3 background-white">--}}
{{--                        <h6 class="card-title text-muted">--}}
{{--                            <i class="fas fa-chart-pie me-1"></i>License Distribution--}}
{{--                        </h6>--}}
{{--                        <p class="text-muted mb-2">--}}
{{--                            Placeholder for license breakdown across published and draft datasets.--}}
{{--                        </p>--}}
{{--                        <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"--}}
{{--                             style="height:220px;">--}}
{{--                            Chart placeholder--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-12 col-lg-7">--}}
{{--                <div class="card border-0 shadow-sm h-100">--}}
{{--                    <div class="card-body p-3 background-white">--}}
{{--                        <h6 class="card-title text-muted">--}}
{{--                            <i class="fas fa-exclamation-triangle me-1"></i>License Issues--}}
{{--                        </h6>--}}
{{--                        <p class="text-muted mb-3">--}}
{{--                            Placeholder for datasets missing licenses, using custom licenses, or needing review.--}}
{{--                        </p>--}}

{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-hover align-middle mb-0" style="width:100%">--}}
{{--                                <thead class="table-light">--}}
{{--                                <tr>--}}
{{--                                    <th>Dataset</th>--}}
{{--                                    <th>Status</th>--}}
{{--                                    <th>Current License</th>--}}
{{--                                    <th>Issue</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td colspan="4" class="text-muted text-center py-4">--}}
{{--                                        License issue placeholder--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

    {{-- ── Publications ────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-publications"
         role="tabpanel"
         aria-labelledby="my-research-publications-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-file-alt me-1"></i>Publications
                </h6>
                <p class="text-muted mb-3">
                    Placeholder for papers, DOI coverage, datasets linked to papers, datasets missing citations,
                    and publication-readiness indicators.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Publication</th>
                            <th>DOI</th>
                            <th>Linked Datasets</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">
                                Publication overview placeholder
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Collaborators ───────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-collaborators"
         role="tabpanel"
         aria-labelledby="my-research-collaborators-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-users me-1"></i>Collaborators
                </h6>
                <p class="text-muted mb-3">
                    Placeholder for project collaborators, dataset co-authors, frequent collaborators,
                    team members, and access roles.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Collaborator</th>
                            <th>Relationship</th>
                            <th>Projects</th>
                            <th>Datasets</th>
                            <th>Role / Access</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">
                                Collaborator overview placeholder
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Metadata ────────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-my-research-metadata"
         role="tabpanel"
         aria-labelledby="my-research-metadata-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-clipboard-check me-1"></i>Metadata Readiness
                </h6>
                <p class="text-muted mb-3">
                    Placeholder for datasets missing descriptions, authors, tags, licenses, DOIs, publication
                    metadata, or other required information.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Missing / Incomplete</th>
                            <th>Priority</th>
                            <th>Suggested Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">
                                Metadata readiness placeholder
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const TAB_KEY = 'mc_dashboard_my_research_tab';

            document.querySelectorAll('#my-research-tabs [data-bs-toggle="pill"]').forEach(btn => {
                btn.addEventListener('shown.bs.tab', function () {
                    localStorage.setItem(TAB_KEY, this.getAttribute('data-bs-target'));

                    if (window.Plotly) {
                        document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                    }
                });
            });

            const savedTab = localStorage.getItem(TAB_KEY);
            if (savedTab) {
                const tabEl = document.querySelector('#my-research-tabs [data-bs-target="' + savedTab + '"]');
                if (tabEl) {
                    Tab.getOrCreateInstance(tabEl).show();
                }
            }
        })();
    </script>
@endpush
