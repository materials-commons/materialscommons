@php
    use Illuminate\Support\Str;

    $attrRows = [];
    $unitCounts = [];
    $numericAttrs = [];

    foreach ($activityAttributes as $name => $attrs) {
        $unitVal = $units($attrs);
        $minVal = $min($attrs);
        $maxVal = $max($attrs);
        $cnt = $attrs->count();

        $attrRows[] = [
            'name' => $name,
            'url' => $activityAttributeRoute($name),
            'units' => $unitVal,
            'min' => $minVal,
            'max' => $maxVal,
            'count' => $cnt,
        ];

        $unitKey = blank($unitVal) ? 'None' : $unitVal;
        $unitCounts[$unitKey] = ($unitCounts[$unitKey] ?? 0) + 1;

        if (is_numeric($minVal) && is_numeric($maxVal) && $minVal != $maxVal) {
            $numericAttrs[] = [
                'name' => strlen($name) > 28 ? substr($name, 0, 26) . '…' : $name,
                'units' => $unitVal,
                'min' => (float) $minVal,
                'max' => (float) $maxVal,
                'count' => $cnt,
            ];
        }
    }

    usort($attrRows, fn($a, $b) => $b['count'] <=> $a['count']);

    $numericByUnit = collect($numericAttrs)
        ->groupBy('units')
        ->map(fn($g) => $g->sortByDesc('count')->take(15)->values())
        ->filter(fn($g) => $g->count() > 0);

    $totalAttrs = count($attrRows);
    $totalValues = array_sum(array_column($attrRows, 'count'));
    $attrsWithUnits = count(array_filter($attrRows, fn($r) => !blank($r['units'])));
    $numericCount = count($numericAttrs);
    $coverageBars = array_slice($attrRows, 0, 20);
@endphp


<div class="row g-2 mb-3">
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Attributes</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($totalAttrs) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in this project</div>
        </div>
    </div>

    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Values</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($totalValues) }}</div>
            <div class="text-muted" style="font-size:.65rem;">across all attrs</div>
        </div>
    </div>

    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">With Units</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($attrsWithUnits) }}</div>
            <div class="text-muted" style="font-size:.65rem;">have unit labels</div>
        </div>
    </div>

    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Numeric Range</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($numericCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">have min &amp; max</div>
        </div>
    </div>
</div>

{{-- ── Analytics toggle header ──────────────────────────────────────────── --}}
<div class="d-flex align-items-center mb-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="epa-analytics-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#epa-analytics"
            aria-expanded="false"
            aria-controls="epa-analytics">
        <i class="fas fa-chevron-right fa-fw"
           id="epa-analytics-chevron"
           style="transition: transform 0.2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-1" id="epa-analytics">

    @if($totalAttrs > 0)
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-poll-h me-1"></i> Value Coverage per Attribute
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            How many measurement values each attribute has — sorted highest first
                            @if(count($attrRows) > 20)
                                , showing top 20
                            @endif
                        </p>
                        <div id="chart-activity-coverage"
                             style="height:{{ min(60 + count($coverageBars) * 26, 500) }}px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-ruler me-1"></i> Units Breakdown
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            Distribution of measurement unit types across all attributes
                        </p>
                        <div id="chart-activity-units" style="height:240px;"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($numericCount > 0)
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-arrows-alt-h me-1"></i> Measurement Ranges (numeric attributes)
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            Each bar spans min → max. Hover for exact values.
                            Grouped by unit so axes are consistent.
                            Up to 15 attributes shown per unit group.
                        </p>

                        @foreach($numericByUnit as $unit => $group)
                            <div class="mb-2">
                                @if($numericByUnit->count() > 1)
                                    <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600;">
                                        {{ blank($unit) ? 'No unit' : "Unit: {$unit}" }}
                                    </div>
                                @endif

                                <div id="chart-activity-range-{{ Str::slug($unit ?: 'none') }}"
                                     style="height:{{ min(50 + $group->count() * 28, 440) }}px;"></div>
                            </div>
                            <hr/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<x-table-container>
    <br>
    <h5>Processes Attributes</h5>
    <br>
    @include('partials.datadictionary._activity_attributes')
</x-table-container>

@push('scripts')
    <script>
        (function () {
            (function () {
                const STORAGE_KEY = 'mc_epa_analytics_open';
                const panel = document.getElementById('epa-analytics');
                const chevron = document.getElementById('epa-analytics-chevron');
                const toggle = document.getElementById('epa-analytics-toggle');

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

            const plotConfig = {
                responsive: true,
                displayModeBar: false
            };

            const base = (extra) => Object.assign({
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {
                    family: 'inherit',
                    size: 11
                },
                showlegend: false
            }, extra);

            @if($totalAttrs > 0)
            @php
                $covNames = array_column($coverageBars, 'name');
                $covCounts = array_column($coverageBars, 'count');
            @endphp

            Plotly.newPlot('chart-activity-coverage', [{
                type: 'bar',
                orientation: 'h',
                y: @json($covNames),
                x: @json($covCounts),
                marker: {
                    color: '#0dcaf0'
                },
                hovertemplate: '%{y}: %{x:,} value(s)<extra></extra>',
                text: @json(array_map(fn($c) => number_format($c), $covCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {
                    color: '#000',
                    size: 9
                }
            }], base({
                margin: {
                    t: 5,
                    b: 30,
                    l: 140,
                    r: 20
                },
                xaxis: {
                    tickformat: ',d',
                    tickfont: {
                        size: 9
                    },
                    gridcolor: '#dee2e6'
                },
                yaxis: {
                    autorange: 'reversed',
                    tickfont: {
                        size: 10
                    }
                }
            }), plotConfig);

            @php
                arsort($unitCounts);
                $unitLabels = array_keys($unitCounts);
                $unitValues = array_values($unitCounts);
            @endphp

            @if(count($unitLabels) > 0)
            Plotly.newPlot('chart-activity-units', [{
                type: 'pie',
                hole: 0.5,
                labels: @json($unitLabels),
                values: @json($unitValues),
                textinfo: 'label+value',
                hoverinfo: 'label+value+percent',
                textfont: {
                    size: 10
                }
            }], base({
                margin: {
                    t: 10,
                    b: 10,
                    l: 10,
                    r: 10
                },
                showlegend: false
            }), plotConfig);
            @endif
            @endif

            @if($numericCount > 0)
            @foreach($numericByUnit as $unit => $group)
            @php
                $rangeNames = $group->pluck('name')->values()->toArray();
                $rangeMins = $group->pluck('min')->values()->toArray();
                $rangeMaxes = $group->pluck('max')->values()->toArray();
                $rangeWidths = array_map(fn($mn, $mx) => round($mx - $mn, 4), $rangeMins, $rangeMaxes);
                $rangeHover = array_map(
                    fn($n, $mn, $mx, $u) => $n . '<br>Min: ' . $mn . ' ' . $u . '<br>Max: ' . $mx . ' ' . $u,
                    $rangeNames,
                    $rangeMins,
                    $rangeMaxes,
                    array_fill(0, count($rangeNames), $unit)
                );
                $slugUnit = Str::slug($unit ?: 'none');
            @endphp

            Plotly.newPlot('chart-activity-range-{{ $slugUnit }}', [{
                type: 'bar',
                orientation: 'h',
                y: @json($rangeNames),
                x: @json($rangeMins),
                marker: {
                    color: 'rgba(0,0,0,0)'
                },
                hoverinfo: 'skip'
            }, {
                type: 'bar',
                orientation: 'h',
                y: @json($rangeNames),
                x: @json($rangeWidths),
                base: @json($rangeMins),
                marker: {
                    color: '#0d6efd',
                    opacity: 0.85
                },
                text: @json($rangeHover),
                hovertemplate: '%{text}<extra></extra>'
            }], base({
                barmode: 'overlay',
                margin: {
                    t: 5,
                    b: 35,
                    l: 140,
                    r: 20
                },
                xaxis: {
                    title: {
                        text: '{{ addslashes($unit ?: "value") }}',
                        font: {
                            size: 10
                        }
                    },
                    tickfont: {
                        size: 9
                    },
                    gridcolor: '#dee2e6'
                },
                yaxis: {
                    autorange: 'reversed',
                    tickfont: {
                        size: 10
                    }
                }
            }), plotConfig);
            @endforeach
            @endif
        })();
    </script>
@endpush
