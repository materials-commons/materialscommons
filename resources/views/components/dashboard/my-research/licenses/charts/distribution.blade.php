{{-- resources/views/components/dashboard/my-research/licenses/charts/distribution.blade.php --}}
@props([
    'licenseDistribution' => collect(),
    'licenseRows' => collect(),
])

@php
    $chartId = 'my-research-license-distribution-chart-' . uniqid();
    $modalId = 'my-research-license-distribution-modal-' . uniqid();

    $licenseDistribution = collect($licenseDistribution);
    $licenseRows = collect($licenseRows);

    $labels = $licenseDistribution->keys()->values();
    $values = $licenseDistribution->values();
    $hasData = $values->sum() > 0;

    $modalRows = $licenseRows
        ->groupBy('license')
        ->map(fn($rows) => collect($rows)->count())
        ->sortDesc();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>License Distribution
                </h6>
                <p class="text-muted mb-0" style="font-size:.8rem;">
                    Distribution across datasets. Click the chart to view license details.
                </p>
            </div>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $modalId }}">
                Details
            </button>
        </div>

        @if($hasData)
            <div id="{{ $chartId }}" class="js-plotly-plot" style="height:280px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:280px;">
                No license data
            </div>
        @endif
    </div>
</div>

<div class="modal fade"
     id="{{ $modalId }}"
     tabindex="-1"
     aria-labelledby="{{ $modalId }}-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}-label">
                    <i class="fas fa-balance-scale me-1"></i>License Distribution Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>License</th>
                            <th class="text-end">Datasets</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($modalRows as $license => $count)
                            <tr>
                                <td>{{ $license }}</td>
                                <td class="text-end">{{ number_format($count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted text-center py-4">
                                    No license data found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($hasData)
    @push('scripts')
        <script>
            (function () {
                const render = function () {
                    const div = document.getElementById(@json($chartId));

                    if (!div || !window.Plotly || div.dataset.rendered === 'true') {
                        return;
                    }

                    div.dataset.rendered = 'true';

                    Plotly.newPlot(div, [{
                        labels: @json($labels),
                        values: @json($values),
                        type: 'pie',
                        hole: 0.45,
                        textinfo: 'label+value',
                        hoverinfo: 'label+value+percent'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: true,
                        legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.18},
                        margin: {t: 10, b: 55, l: 10, r: 10}
                    }, {
                        responsive: true,
                        displayModeBar: false
                    });

                    div.on('plotly_click', function () {
                        const modalEl = document.getElementById(@json($modalId));

                        if (modalEl) {
                            Modal.getOrCreateInstance(modalEl).show();
                        }
                    });
                };

                document.addEventListener('DOMContentLoaded', render);
                document.getElementById('my-research-licenses-tab')?.addEventListener('shown.bs.tab', render);
            })();
        </script>
    @endpush
@endif
