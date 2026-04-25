{{--
  Prototype v2 Sample Attributes tab.
  To try it: in show.blade.php change
      @include('app.projects.tabs.entity-attributes')
  to
      @include('app.projects.tabs.entity-attributes-v2')

  Adds three charts above the existing DataTable (table is unchanged):
    1. Value Coverage — horizontal bar: how many measurement values each
                        attribute has, sorted highest first.
    2. Units breakdown — donut: distribution of unit types across attributes.
    3. Measurement Ranges — floating bar (min→max) for numeric attributes.
                            Grouped by unit so axes are apples-to-apples.
                            Only rendered when numeric min/max data exists.
--}}

@php
    // ── Pre-process attribute data once so charts and table share the same pass ──
    use Illuminate\Support\Str;
    $attrRows     = [];
    $unitCounts   = [];   // unit => count of attributes using that unit
    $numericAttrs = [];   // attributes with parseable numeric min & max

    foreach ($entityAttributes as $name => $attrs) {
        $unitVal  = $units($attrs);
        $minVal   = $min($attrs);
        $maxVal   = $max($attrs);
        $cnt      = $attrs->count();

        $attrRows[] = [
            'name'  => $name,
            'url'   => $entityAttributeRoute($name),
            'units' => $unitVal,
            'min'   => $minVal,
            'max'   => $maxVal,
            'count' => $cnt,
        ];

        // Units tally (blank → "None")
        $unitKey = blank($unitVal) ? 'None' : $unitVal;
        $unitCounts[$unitKey] = ($unitCounts[$unitKey] ?? 0) + 1;

        // Collect numeric attributes for the range chart
        if (is_numeric($minVal) && is_numeric($maxVal) && $minVal != $maxVal) {
            $numericAttrs[] = [
                'name'  => strlen($name) > 28 ? substr($name, 0, 26).'…' : $name,
                'units' => $unitVal,
                'min'   => (float) $minVal,
                'max'   => (float) $maxVal,
                'count' => $cnt,
            ];
        }
    }

    // Sort coverage bars: most values first
    usort($attrRows, fn($a, $b) => $b['count'] <=> $a['count']);

    // Group numeric attributes by unit for the range chart
    $numericByUnit = collect($numericAttrs)
        ->groupBy('units')
        ->map(fn($g) => $g->sortByDesc('count')->take(15)->values())
        ->filter(fn($g) => $g->count() > 0);

    $totalAttrs  = count($attrRows);
    $totalValues = array_sum(array_column($attrRows, 'count'));
    $attrsWithUnits = count(array_filter($attrRows, fn($r) => !blank($r['units'])));
    $numericCount   = count($numericAttrs);

    // Coverage bar: top 20 by count
    $coverageBars = array_slice($attrRows, 0, 20);
@endphp

{{-- ══════════════════════════════════════════════════════════════════════════
     KPI strip
     ══════════════════════════════════════════════════════════════════════════ --}}
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

@if($totalAttrs > 0)

    {{-- ══════════════════════════════════════════════════════════════════════════
         Row 1: Coverage bar + Units donut
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-3">

        <div class="col-12 col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <h6 class="card-title text-muted mb-0">
                        <i class="fas fa-poll-h me-1"></i> Value Coverage per Attribute
                    </h6>
                    <p class="text-muted mb-1" style="font-size:.7rem;">
                        How many measurement values each attribute has — sorted highest first
                        @if(count($attrRows) > 20)
                            , showing top 20
                        @endif
                    </p>
                    <div id="chart-entity-coverage"
                         style="height:{{ min(60 + count($coverageBars) * 26, 500) }}px;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <h6 class="card-title text-muted mb-0">
                        <i class="fas fa-ruler me-1"></i> Units Breakdown
                    </h6>
                    <p class="text-muted mb-1" style="font-size:.7rem;">
                        Distribution of measurement unit types across all attributes
                    </p>
                    <div id="chart-entity-units" style="height:240px;"></div>
                </div>
            </div>
        </div>

    </div>
@endif {{-- totalAttrs > 0 --}}
{{-- ══════════════════════════════════════════════════════════════════════════
     Row 2: Measurement Range chart (only when numeric data exists)
     ══════════════════════════════════════════════════════════════════════════ --}}
@if($numericCount > 0)
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title text-muted mb-0">
                        <i class="fas fa-arrows-alt-h me-1"></i> Measurement Ranges (numeric attributes)
                    </h6>
                    <p class="text-muted mb-1" style="font-size:.7rem;">
                        Each bar spans min → max. Hover for exact values.
                        Grouped by unit so axes are consistent.
                        Up to 15 attributes shown per unit group.
                    </p>

                    {{-- One chart div per unit group --}}
                    @foreach($numericByUnit as $unit => $group)
                        <div class="mb-2">
                            @if($numericByUnit->count() > 1)
                                <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600;">
                                    {{ blank($unit) ? 'No unit' : "Unit: {$unit}" }}
                                </div>
                            @endif
                            <div id="chart-entity-range-{{ Str::slug($unit ?: 'none') }}"
                                 style="height:{{ min(50 + $group->count() * 28, 440) }}px;"></div>
                        </div>
                        <hr/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif



{{-- ══════════════════════════════════════════════════════════════════════════
     Original DataTable — unchanged
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="text-muted mb-3">
            <i class="fas fa-table me-1"></i> All Sample Attributes
        </h6>
        <table id="entities-dd" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Attribute</th>
                <th>Units</th>
                <th>Min</th>
                <th>Max</th>
                <th># Values</th>
            </tr>
            </thead>
            <tbody>
            @foreach($entityAttributes as $name => $attrs)
                <tr>
                    <td><a href="{{$entityAttributeRoute($name)}}">{{$name}}</a></td>
                    <td>{{$units($attrs)}}</td>
                    <td>{{$min($attrs)}}</td>
                    <td>{{$max($attrs)}}</td>
                    <td>{{$attrs->count()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const plotConfig = {responsive: true, displayModeBar: false};
            const base = (extra) => Object.assign({
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11},
                showlegend: false,
            }, extra);

            @if($totalAttrs > 0)

            // ── 1. Coverage bar ───────────────────────────────────────────────────
            @php
                $covNames  = array_column($coverageBars, 'name');
                $covCounts = array_column($coverageBars, 'count');
            @endphp
            Plotly.newPlot('chart-entity-coverage', [{
                type: 'bar',
                orientation: 'h',
                y:             @json($covNames),
                x:             @json($covCounts),
                marker: {color: '#0d6efd'},
                hovertemplate: '%{y}: %{x:,} value(s)<extra></extra>',
                text:          @json(array_map(fn($c) => number_format($c), $covCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 140, r: 20},
                xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif {{-- totalAttrs > 0 --}}
            // ── 2. Units donut ────────────────────────────────────────────────────
            @php
                arsort($unitCounts);
                $unitLabels = array_keys($unitCounts);
                $unitValues = array_values($unitCounts);
            @endphp
            @if(count($unitLabels) > 0)
            Plotly.newPlot('chart-entity-units', [{
                type: 'pie',
                hole: 0.5,
                labels:    @json($unitLabels),
                values:    @json($unitValues),
                textinfo: 'label+value',
                hoverinfo: 'label+value+percent',
                textfont: {size: 10},
            }], base({
                margin: {t: 10, b: 10, l: 10, r: 10},
                showlegend: false,
            }), plotConfig);
            @endif

            // ── 3. Range charts (one per unit group) ─────────────────────────────
            @if($numericCount > 0)
            @foreach($numericByUnit as $unit => $group)
            @php
                $rangeNames  = $group->pluck('name')->values()->toArray();
                $rangeMins   = $group->pluck('min')->values()->toArray();
                $rangeMaxes  = $group->pluck('max')->values()->toArray();
                $rangeWidths = array_map(fn($mn, $mx) => round($mx - $mn, 4), $rangeMins, $rangeMaxes);
                $rangeHover  = array_map(
                    fn($n, $mn, $mx, $u) => $n . '<br>Min: ' . $mn . ' ' . $u . '<br>Max: ' . $mx . ' ' . $u,
                    $rangeNames, $rangeMins, $rangeMaxes,
                    array_fill(0, count($rangeNames), $unit)
                );
                $slugUnit = Str::slug($unit ?: 'none');
            @endphp
            Plotly.newPlot('chart-entity-range-{{ $slugUnit }}', [{
                // Invisible base bar anchors the floating bar at the min value
                type: 'bar',
                orientation: 'h',
                y:             @json($rangeNames),
                x:             @json($rangeMins),
                marker: {color: 'rgba(0,0,0,0)'},
                hoverinfo: 'skip',
                showlegend: false,
            }, {
                // The visible range bar spans (max - min), starting at min
                type: 'bar',
                orientation: 'h',
                y:             @json($rangeNames),
                x:             @json($rangeWidths),
                base:          @json($rangeMins),
                name: '{{ addslashes($unit ?: "No unit") }}',
                marker: {color: '#0dcaf0', opacity: 0.85},
                text:          @json($rangeHover),
                hovertemplate: '%{text}<extra></extra>',
            }], base({
                barmode: 'overlay',
                margin: {t: 5, b: 35, l: 140, r: 20},
                xaxis: {
                    title: {text: '{{ addslashes($unit ?: "value") }}', font: {size: 10}},
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endforeach
            @endif



            // DataTable
            document.addEventListener('livewire:navigating', () => {
                $('#entities-dd').DataTable().destroy();
            }, {once: true});
            $(document).ready(() => {
                $('#entities-dd').DataTable({pageLength: 100, stateSave: true});
            });
        })();
    </script>
@endpush
