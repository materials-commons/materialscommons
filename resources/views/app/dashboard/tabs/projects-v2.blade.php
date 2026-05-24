{{--
  Prototype v2 Projects tab layout.
  To try it: in index.blade.php change
      @include('app.dashboard.tabs.projects')
  to
      @include('app.dashboard.tabs.projects-v2')

  Changes vs. original:
    • KPI stat cards replace the old "Projects Overview" card
    • Analytics section (5 Plotly charts) is collapsible — defaults closed
    • Active + Recently Accessed sidebar replaced by filter tabs on the
      All Projects table (All / Starred / Recent)
    • Full-width table — no sidebar wasted space
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
<div class="collapse mb-3" id="dashboard-analytics">
    @include('app.dashboard.tabs.projects._dashboard-charts')
</div>

{{-- ── Full-width projects table with filter tabs ─────────────────────────── --}}
<div role="region" aria-label="Projects">
    <x-table-container>
        @include('app.dashboard.tabs.projects._projects-table')
    </x-table-container>
</div>

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = 'mc_dashboard_analytics_open';
            const panel = document.getElementById('dashboard-analytics');
            const chevron = document.getElementById('analytics-chevron');
            const toggle = document.getElementById('analytics-toggle');

            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                panel.classList.add('show');
                chevron.style.transform = 'rotate(90deg)';
                toggle.setAttribute('aria-expanded', 'true');
            }

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
