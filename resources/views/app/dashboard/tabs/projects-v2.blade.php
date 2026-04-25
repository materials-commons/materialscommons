{{--
  Prototype v2 Projects tab layout.
  To try it: in index.blade.php change
      @include('app.dashboard.tabs.projects')
  to
      @include('app.dashboard.tabs.projects-v2')

  Changes vs. original:
    • KPI stat cards replace the old "Projects Overview" card
    • Analytics section (5 Plotly charts) is collapsible — defaults closed
      so users land directly on their projects list. Preference persisted
      in localStorage so power users who open it keep it open.
    • "Create New Project" CTA lives in the tabs bar (tabs-v2.blade.php)
    • Active Projects and Recently Accessed cards are unchanged
    • All Projects table is unchanged
    • Layout is fully responsive (col stacking at sm/md breakpoints)
    • ARIA landmarks added to card sections
--}}

{{-- ── Row 1: KPI cards — always visible ────────────────────────────────── --}}
@include('app.dashboard.tabs.projects._kpi-cards')

{{-- ── Analytics toggle header ───────────────────────────────────────────── --}}
<div class="d-flex align-items-center mb-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="analytics-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#dashboard-analytics"
            aria-expanded="false"
            aria-controls="dashboard-analytics">
        <i class="fas fa-chevron-right fa-fw"
           id="analytics-chevron"
           style="transition: transform 0.2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

{{-- ── Analytics charts — collapsed by default ───────────────────────────── --}}
<div class="collapse mb-1" id="dashboard-analytics">
    @include('app.dashboard.tabs.projects._dashboard-charts')
</div>

{{-- ── Sidebar + All-projects table — always visible ─────────────────────── --}}
<div class="row g-3">

    {{-- Left sidebar: active + recently accessed --}}
    <div class="col-12 col-lg-4" role="complementary" aria-label="Project shortcuts">
        @include('app.dashboard.tabs.projects._active-projects')
        @include('app.dashboard.tabs.projects._recently-accessed-projects')
    </div>

    {{-- Right: all projects DataTable --}}
    <div class="col-12 col-lg-8" role="region" aria-label="All projects">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @include('app.dashboard.tabs.projects._projects-table')
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = 'mc_dashboard_analytics_open';
            const panel = document.getElementById('dashboard-analytics');
            const chevron = document.getElementById('analytics-chevron');
            const toggle = document.getElementById('analytics-toggle');

            // Restore saved preference before the page paints
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                panel.classList.add('show');
                chevron.style.transform = 'rotate(90deg)';
                toggle.setAttribute('aria-expanded', 'true');
            }

            // Keep chevron in sync and persist state whenever Bootstrap toggles the panel
            panel.addEventListener('show.bs.collapse', () => {
                chevron.style.transform = 'rotate(90deg)';
                localStorage.setItem(STORAGE_KEY, 'true');
            });
            panel.addEventListener('hide.bs.collapse', () => {
                chevron.style.transform = 'rotate(0deg)';
                localStorage.setItem(STORAGE_KEY, 'false');
            });
            panel.addEventListener('shown.bs.collapse', () => {
                panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
            });
        })();
    </script>
@endpush
