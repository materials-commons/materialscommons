@props([
    'collaborators' => collect(),
])

@php
    $collaborators = collect($collaborators);
    $chartId = 'my-research-project-collaborators-chart-' . uniqid();
    $listId = 'my-research-project-collaborators-list-' . uniqid();

    $labels = $collaborators->pluck('name')->values();
    $values = $collaborators->pluck('project_count')->values();

    $details = $collaborators
        ->mapWithKeys(function ($collaborator) {
            return [
                $collaborator['name'] => collect($collaborator['projects'])
                    ->filter(fn($project) => filled($project['project_id'] ?? null))
                    ->map(fn($project) => [
                        'name' => $project['project_name'],
                        'role' => $project['role'] ?? null,
                        'url' => route('projects.show', [$project['project_id']]),
                    ])
                    ->values(),
            ];
        });
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-bar me-1"></i>Frequent Project Collaborators
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Click a bar to show shared projects.
                </p>
            </div>
        </div>

        <div id="{{ $chartId }}" class="js-plotly-plot" style="height:260px;"></div>

        <div id="{{ $listId }}" class="border rounded p-2 mt-2 d-none">
            <div class="fw-semibold mb-2" data-collaborator-title></div>
            <div data-collaborator-items></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const el = document.getElementById(@json($chartId));
            const list = document.getElementById(@json($listId));
            const details = @json($details);

            if (!el || typeof Plotly === 'undefined') return;

            Plotly.newPlot(el, [{
                type: 'bar',
                orientation: 'h',
                x: @json($values),
                y: @json($labels),
                marker: { color: '#0dcaf0' },
                hovertemplate: '%{y}: %{x} project(s)<extra></extra>'
            }], {
                margin: { t: 10, b: 35, l: 145, r: 15 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
                xaxis: {
                    tickformat: 'd',
                    dtick: 1,
                    title: { text: 'Projects', font: { size: 11 } }
                },
                yaxis: { automargin: true }
            }, {
                responsive: true,
                displayModeBar: false
            });

            el.on('plotly_click', function (event) {
                if (!list || !event.points || !event.points.length) return;

                const collaborator = event.points[0].y;
                const items = details[collaborator] || [];

                list.classList.remove('d-none');
                list.querySelector('[data-collaborator-title]').textContent = collaborator + ' — shared projects';

                list.querySelector('[data-collaborator-items]').innerHTML = items.length
                    ? items.map(item => {
                        const role = item.role ? `<span class="badge text-bg-light border text-muted ms-1">${item.role}</span>` : '';
                        return `<div><a href="${item.url}" class="text-decoration-none">${item.name}</a>${role}</div>`;
                    }).join('')
                    : '<div class="text-muted">No projects found.</div>';
            });
        })();
    </script>
@endpush
