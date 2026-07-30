
@include('app.projects.tabs._home-proj-overview')

{{-- ══════════════════════════════════════════════════════════════════════════
     1. KPI STAT CARDS — always visible
     ══════════════════════════════════════════════════════════════════════════ --}}
<x-collapsible-section title="KPI" id="project-kpi" storageKey="mc_proj_home_kpi">
    @include('app.projects.tabs._home-kpi')
</x-collapsible-section>

{{-- ══════════════════════════════════════════════════════════════════════════
     2. CHAT — collapsible, default CLOSED
     ══════════════════════════════════════════════════════════════════════════ --}}
@if (isInBeta('ai-chat'))
    @include('app.projects.tabs._ai-semantic')
    @include('app.projects.tabs._ai-extraction')
    @include('app.projects.tabs._ai-transformation')
    @include('app.projects.tabs._ai-chat')
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     3. ANALYTICS — collapsible, default CLOSED
     ══════════════════════════════════════════════════════════════════════════ --}}
<x-collapsible-section title="Analytics"
                       id="project-analytics"
                       storageKey="mc_proj_home_analytics"
                       :resize-plotly="true">
    @include('app.projects.tabs.home._project-charts')
</x-collapsible-section>

{{-- ══════════════════════════════════════════════════════════════════════════
     4. QUICK ACTIONS — collapsible, default OPEN
     ══════════════════════════════════════════════════════════════════════════ --}}

<x-collapsible-section title="Quick Actions"
                       id="project-actions"
                       storageKey="mc_proj_home_actions">
    @include('app.projects.tabs._home-quick-actions')
</x-collapsible-section>

{{-- ══════════════════════════════════════════════════════════════════════════
 5. GETTING STARTED / DOCUMENTATION — collapsible, default OPEN
 ══════════════════════════════════════════════════════════════════════════ --}}
<x-collapsible-section title="Getting Started"
                       id="project-getting-started"
                       storageKey="mc_proj_home_getting_started"
                       :open="true">
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
</x-collapsible-section>

{{-- ══════════════════════════════════════════════════════════════════════════
 6. README — always visible
 ══════════════════════════════════════════════════════════════════════════ --}}
<x-display-markdown-file :file="$readme"></x-display-markdown-file>

{{-- ══════════════════════════════════════════════════════════════════════════
 Collapse state management (localStorage, per-project)
 ══════════════════════════════════════════════════════════════════════════ --}}
@push('styles')
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
