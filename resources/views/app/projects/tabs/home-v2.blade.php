{{--
  Prototype v2 project Home tab.
  To try it: in show.blade.php change
      @include('app.projects.tabs.home')
  to
      @include('app.projects.tabs.home-v2')

  Layout (top to bottom):
    1. KPI stat cards         — always visible, at-a-glance project numbers
    2. ▶ Chat                 — collapsible, default CLOSED  (LLM chat interface)
    3. ▶ Analytics            — collapsible, default CLOSED  (3 Plotly charts)
    4. ▶ Quick Actions        — collapsible, default OPEN    (action buttons)
    5. ▶ Getting Started      — collapsible, default CLOSED  (original 3 feature cards)
    6. README                 — always visible               (existing component)

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
                <div class="card-body text-center p-2 background-white">
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
                <div class="card-body text-center p-2 background-white">
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
                <div class="card-body text-center p-2 background-white">
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
                <div class="card-body text-center p-2 background-white">
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
            <div class="card-body text-center p-2 background-white">
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
                <div class="card-body text-center p-2 background-white">
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

{{-- ══════════════════════════════════════════════════════════════════════════
     2. CHAT — collapsible, default CLOSED
     ══════════════════════════════════════════════════════════════════════════ --}}
@if (isInBeta('ai-chat'))
    <div class="d-flex align-items-center mb-2 mt-3">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="proj-chat-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#proj-chat"
                aria-expanded="false"
                aria-controls="proj-chat">
            <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Chat
        </span>
            <span class="badge rounded-pill ms-1" style="font-size:.65rem; background:#6366f1; color:#fff;">AI</span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <div class="collapse mb-1" id="proj-chat"
         data-mc-collapse-key="{{$projKey}}_chat">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; overflow:hidden;">
            {{-- Chat header --}}
            <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
                 style="background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%); border:none;">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:30px;height:30px;background:rgba(255,255,255,.2); flex-shrink:0;">
                    <i class="fas fa-robot" style="color:#fff; font-size:.75rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-white" style="font-size:.85rem; line-height:1.1;">Project Assistant
                    </div>
                    <div style="font-size:.7rem; color:rgba(255,255,255,.7);">Ask anything about this project</div>
                </div>
                <span class="d-flex align-items-center gap-1" style="font-size:.7rem; color:rgba(255,255,255,.8);">
                <span class="rounded-circle"
                      style="width:7px;height:7px;background:#4ade80;display:inline-block;"></span>
                Ready
            </span>
            </div>

            {{-- Message thread --}}
            <div id="proj-chat-messages"
                 class="px-3 py-3 background-white"
                 style="min-height:260px; max-height:380px; overflow-y:auto; display:flex; flex-direction:column; gap:.75rem;">

                {{-- Assistant greeting bubble --}}
                <div class="d-flex align-items-start gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                         style="width:28px;height:28px;background:#ede9fe;">
                        <i class="fas fa-robot" style="color:#6366f1; font-size:.65rem;"></i>
                    </div>
                    <div>
                        <div class="px-3 py-2 rounded-3"
                             style="background:#f3f4f6; max-width:480px; font-size:.84rem; line-height:1.5;">
                            Hi! I'm your Project Assistant. I can help you explore your data, summarize studies,
                            find files, and answer questions about <strong>{{ $project->name }}</strong>.
                            What would you like to know?
                        </div>
                        <div class="text-muted mt-1" style="font-size:.68rem; padding-left:.25rem;">Just now</div>
                    </div>
                </div>

                {{-- Example user bubble --}}
                <div class="d-flex align-items-start gap-2 flex-row-reverse">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                         style="width:28px;height:28px;background:#dbeafe;">
                        <i class="fas fa-user" style="color:#3b82f6; font-size:.65rem;"></i>
                    </div>
                    <div>
                        <div class="px-3 py-2 rounded-3"
                             style="background:#6366f1; color:#fff; max-width:480px; font-size:.84rem; line-height:1.5;">
                            How many files were uploaded this month?
                        </div>
                        <div class="text-muted mt-1 text-end" style="font-size:.68rem; padding-right:.25rem;">Just now
                        </div>
                    </div>
                </div>

                {{-- Assistant reply bubble --}}
                <div class="d-flex align-items-start gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                         style="width:28px;height:28px;background:#ede9fe;">
                        <i class="fas fa-robot" style="color:#6366f1; font-size:.65rem;"></i>
                    </div>
                    <div>
                        <div class="px-3 py-2 rounded-3"
                             style="background:#f3f4f6; max-width:480px; font-size:.84rem; line-height:1.5;">
                            This project currently has <strong>{{ number_format($project->file_count) }} files</strong>
                            across <strong>{{ number_format($project->directory_count) }} directories</strong>.
                            For a detailed upload timeline, check the <em>Analytics</em> section below.
                        </div>
                        <div class="text-muted mt-1" style="font-size:.68rem; padding-left:.25rem;">Just now</div>
                    </div>
                </div>

            </div>

            {{-- Suggested prompts --}}
            <div class="px-3 pb-2 pt-1 background-white border-top" style="border-color:#f3f4f6 !important;">
                <div class="d-flex flex-wrap gap-2 py-2" style="font-size:.75rem;">
                    <span class="text-muted me-1" style="line-height:1.9;">Try:</span>
                    <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                            style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                            disabled>
                        Summarize recent activity
                    </button>
                    <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                            style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                            disabled>
                        List my studies
                    </button>
                    <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                            style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                            disabled>
                        Find large files
                    </button>
                    <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                            style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                            disabled>
                        What datasets are published?
                    </button>
                </div>
            </div>

            {{-- Input row --}}
            <div class="px-3 pb-3 background-white">
                <div class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1 position-relative">
                    <textarea class="form-control"
                              id="proj-chat-input"
                              rows="2"
                              placeholder="Ask a question about your project…"
                              style="resize:none; border-radius:.6rem; border-color:#e5e7eb; font-size:.84rem; padding-right:2.5rem; line-height:1.45;"
                              disabled></textarea>
                        <span class="position-absolute text-muted"
                              style="right:.6rem; bottom:.5rem; font-size:.65rem; pointer-events:none;">
                        Preview
                    </span>
                    </div>
                    <button type="button"
                            class="btn d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;flex-shrink:0;border-radius:.6rem;background:#6366f1;border:none;"
                            disabled
                            title="Send (coming soon)">
                        <i class="fas fa-paper-plane" style="color:#fff; font-size:.8rem;"></i>
                    </button>
                </div>
                {{--            <div class="text-muted mt-1" style="font-size:.67rem;">--}}
                {{--                <i class="fas fa-info-circle me-1"></i>--}}
                {{--                Chat functionality coming soon — this is a UI preview.--}}
                {{--            </div>--}}
            </div>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     3. ANALYTICS — collapsible, default CLOSED
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
     4. QUICK ACTIONS — collapsible, default OPEN
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-actions-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-actions"
            aria-expanded="true"
            aria-controls="proj-actions">
        <i class="fas fa-chevron-right fa-fw proj-chevron"
           style="transition:transform .2s; font-size:.75rem; transform:rotate(90deg);"></i>
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
        <div class="card-body p-3 background-white">
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
     5. GETTING STARTED / DOCUMENTATION — collapsible, default CLOSED
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
     6. README — always visible
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
                const panel = document.getElementById(panelId);
                const toggle = document.getElementById(toggleId);
                if (!panel || !toggle) return;

                // Find the chevron inside this specific toggle button
                const chevron = toggle.querySelector('.proj-chevron');
                const open = (v) => {
                    chevron.style.transform = 'rotate(90deg)';
                    toggle.setAttribute('aria-expanded', 'true');
                };
                const close = (v) => {
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
                // Plotly charts rendered inside a hidden collapse have zero pixel
                // dimensions at init time.  Resize them once the panel is fully open.
                panel.addEventListener('shown.bs.collapse', () => {
                    panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                });
            }

            wireCollapse('proj-chat', 'proj-chat-toggle', '{{$projKey}}_chat', false);
            wireCollapse('proj-analytics', 'proj-analytics-toggle', '{{$projKey}}_analytics', false);
            wireCollapse('proj-actions', 'proj-actions-toggle', '{{$projKey}}_actions', true);
            wireCollapse('proj-docs', 'proj-docs-toggle', '{{$projKey}}_docs', false);
        })();
    </script>

    <style>
        /* Subtle hover lift on the clickable KPI cards */
        .card-hover {
            transition: transform .15s, box-shadow .15s;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .1) !important;
        }
    </style>
@endpush
