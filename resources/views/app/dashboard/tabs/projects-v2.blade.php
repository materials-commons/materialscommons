{{-- ── Row 1: KPI cards — always visible ────────────────────────────────── --}}
<x-collapsible-section title="KPI" id="kpi-cards" storageKey="mc_dashboard_kpi_cards_open">
    @include('app.dashboard.tabs.projects._kpi-cards')
</x-collapsible-section>


{{-- ── Analytics charts — collapsed by default ───────────────────────────── --}}
<x-collapsible-section title="Analytics" id="dashboard-analytics" storage-key="mc_dashboard_analytics_open"
                       :resize-plotly="true">
    @include('app.dashboard.tabs.projects._dashboard-charts')
</x-collapsible-section>

{{-- ── Full-width projects table with filter tabs ─────────────────────────── --}}
<div role="region" aria-label="Projects">
    <x-table-container>
        @include('app.dashboard.tabs.projects._projects-table')
    </x-table-container>
</div>
