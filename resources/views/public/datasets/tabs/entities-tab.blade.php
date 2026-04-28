@include('public.datasets.tabs._short-overview')
@php
    $dsEntAnalyticsKey = 'mc_pub_ds_' . $dataset->id . '_entities_analytics';
    $totalSamples   = count($entities);
    $totalProcesses = count($activities);

    $processParticipation = [];
    foreach ($activities as $i => $activity) {
        $count = 0;
        foreach ($usedActivities as $usedArr) {
            if (!empty($usedArr[$i])) { $count++; }
        }
        $processParticipation[$activity->name] = $count;
    }
    arsort($processParticipation);
    $procNames  = array_keys(array_slice($processParticipation, 0, 20, true));
    $procCounts = array_values(array_slice($processParticipation, 0, 20, true));

    $depthBuckets = [];
    $totalDepth   = 0;
    foreach ($entities as $entity) {
        $depth = 0;
        if (!empty($usedActivities[$entity->id])) {
            $depth = count(array_filter($usedActivities[$entity->id]));
        }
        $depthBuckets[$depth] = ($depthBuckets[$depth] ?? 0) + 1;
        $totalDepth += $depth;
    }
    ksort($depthBuckets);
    $depthLabels  = array_map(fn($d) => $d === 0 ? 'None' : (string)$d, array_keys($depthBuckets));
    $depthValues  = array_values($depthBuckets);
    $avgDepth     = $totalSamples > 0 ? round($totalDepth / $totalSamples, 1) : 0;
    $fullyCovered = $depthBuckets[0] ?? 0;
    $withProcesses = $totalSamples - $fullyCovered;
    $depthColors  = array_map(fn($d) => $d === 0 ? '#adb5bd' : '#0d6efd', array_keys($depthBuckets));
@endphp

{{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Samples</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($totalSamples) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in dataset</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Process Types</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($totalProcesses) }}</div>
            <div class="text-muted" style="font-size:.65rem;">unique activities</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Avg Process Depth</div>
            <div class="fw-bold fs-5 text-success">{{ $avgDepth }}</div>
            <div class="text-muted" style="font-size:.65rem;">processes per sample</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">With Processes</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($withProcesses) }}</div>
            <div class="text-muted" style="font-size:.65rem;">have ≥1 process</div>
        </div>
    </div>
</div>

{{-- ══ Analytics — collapsible, default CLOSED ════════════════════════════════ --}}
@if($totalProcesses > 0)
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="ds-ent-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#ds-ent-analytics"
                aria-expanded="false"
                aria-controls="ds-ent-analytics">
            <i class="fas fa-chevron-right fa-fw" id="ds-ent-analytics-chevron"
               style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>
    <div class="collapse mb-3" id="ds-ent-analytics">
        <div class="row g-3">
            <div class="col-12 col-md-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-tasks me-1"></i> Process Participation
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            How many samples participated in each process
                            @if(count($processParticipation) > 20)
                                , showing top 20
                            @endif
                        </p>
                        <div id="chart-ds-ent-proc"
                             style="height:{{ min(60 + count($procNames) * 26, 480) }}px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-layer-group me-1"></i> Sample Depth
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            How many processes each sample participated in
                        </p>
                        <div id="chart-ds-ent-depth" style="height:220px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = '{{ $dsEntAnalyticsKey }}';
            const panel = document.getElementById('ds-ent-analytics');
            const toggle = document.getElementById('ds-ent-analytics-toggle');
            const chevron = document.getElementById('ds-ent-analytics-chevron');

            if (!panel) return;

            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                panel.classList.add('show');
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            }
            panel.addEventListener('show.bs.collapse', () => {
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                localStorage.setItem(STORAGE_KEY, 'true');
            });
            panel.addEventListener('hide.bs.collapse', () => {
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                localStorage.setItem(STORAGE_KEY, 'false');
            });
            panel.addEventListener('shown.bs.collapse', () => {
                panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
            });

            const plotConfig = {responsive: true, displayModeBar: false};
            const base = (extra) => Object.assign({
                paper_bgcolor: 'transparent', plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11}, showlegend: false,
            }, extra);

            @if($totalProcesses > 0)
            Plotly.newPlot('chart-ds-ent-proc', [{
                type: 'bar', orientation: 'h',
                y:    @json($procNames),
                x:    @json($procCounts),
                marker: {color: '#0d6efd'},
                hovertemplate: '%{y}: %{x:,} sample(s)<extra></extra>',
                text: @json(array_map(fn($c) => number_format($c), $procCounts)),
                textposition: 'inside', insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 35, l: 160, r: 20},
                xaxis: {
                    tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                    title: {text: 'Samples', font: {size: 10}}
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);

            Plotly.newPlot('chart-ds-ent-depth', [{
                type: 'bar',
                x:    @json($depthLabels),
                y:    @json($depthValues),
                marker: {color: @json($depthColors)},
                hovertemplate: '%{x} process(es): %{y:,} sample(s)<extra></extra>',
                text: @json(array_map(fn($v) => number_format($v), $depthValues)),
                textposition: 'outside', textfont: {size: 9},
            }], base({
                margin: {t: 20, b: 40, l: 40, r: 15},
                xaxis: {title: {text: '# Processes', font: {size: 10}}, tickfont: {size: 9}},
                yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
            }), plotConfig);
            @endif
        })();
    </script>
@endpush

<table id="entities-with-used-activities" class="table table-hover" style="width: 100%">
    <thead>
    <th>Name</th>
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>
                <a href="{{route('public.datasets.entities.show', [$dataset, $entity])}}">
                    {{$entity->name}}
                </a>
            </td>
            @foreach($usedActivities[$entity->id] as $used)
                @if($used)
                    <td>X</td>
                    {{--                    <td>{{$entity->name}}</td>--}}
                    {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                @else
                    <td></td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                stateSave: true,
                scrollX: true,
            });
        });
    </script>
@endpush
