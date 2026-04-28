@include('public.datasets.tabs._short-overview')
@php
    $dsDirAnalyticsKey = 'mc_pub_ds_' . $dataset->id . '_dir_' . $directory->id . '_analytics';
    $fileCount  = 0;
    $dirCount   = 0;
    $imageCount = 0;
    $totalSize  = 0;
    $typeCounts = [];
    $topBySize  = [];

    foreach ($files as $f) {
        if ($f->isDir()) {
            $dirCount++;
        } else {
            $fileCount++;
            $totalSize += (int)$f->size;
            if ($f->isImage()) { $imageCount++; }
            $typeDesc = $f->mime_type ?: 'unknown';
            $typeCounts[$typeDesc] = ($typeCounts[$typeDesc] ?? 0) + 1;
            $topBySize[] = [
                'name'  => strlen($f->name) > 35 ? substr($f->name, 0, 33) . '…' : $f->name,
                'size'  => (int)$f->size,
                'human' => $f->toHumanBytes(),
            ];
        }
    }
    arsort($typeCounts);
    $typeLabels = array_keys(array_slice($typeCounts, 0, 15, true));
    $typeValues = array_values(array_slice($typeCounts, 0, 15, true));

    usort($topBySize, fn($a, $b) => $b['size'] <=> $a['size']);
    $topBySize = array_slice($topBySize, 0, 10);
    $topNames  = array_column($topBySize, 'name');
    $topSizes  = array_column($topBySize, 'size');
    $topHuman  = array_column($topBySize, 'human');
@endphp

{{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Files</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($fileCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in this folder</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Subdirectories</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($dirCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">sub-folders</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Size</div>
            <div class="fw-bold fs-5 text-success">{{ formatBytes($totalSize) }}</div>
            <div class="text-muted" style="font-size:.65rem;">files in folder</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Images</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($imageCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">image files</div>
        </div>
    </div>
</div>

{{-- ══ Analytics — collapsible, default CLOSED ════════════════════════════════ --}}
@if($fileCount > 0)
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="ds-folder-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#ds-folder-analytics"
                aria-expanded="false"
                aria-controls="ds-folder-analytics">
            <i class="fas fa-chevron-right fa-fw" id="ds-folder-analytics-chevron"
               style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>
    <div class="collapse mb-3" id="ds-folder-analytics">
        <div class="row g-3">
            @if(count($typeLabels) > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-file-alt me-1"></i> File Types
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Distribution of MIME types in this folder
                                @if(count($typeCounts) > 15)
                                    , showing top 15
                                @endif
                            </p>
                            <div id="chart-dsfolder-types"
                                 style="height:{{ min(60 + count($typeLabels) * 26, 400) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($topBySize) > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-weight me-1"></i> Largest Files
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Top {{ count($topBySize) }} files by size
                            </p>
                            <div id="chart-dsfolder-sizes"
                                 style="height:{{ min(60 + count($topBySize) * 26, 320) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = '{{ $dsDirAnalyticsKey }}';
            const panel = document.getElementById('ds-folder-analytics');
            const toggle = document.getElementById('ds-folder-analytics-toggle');
            const chevron = document.getElementById('ds-folder-analytics-chevron');

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

            @if(count($typeLabels) > 0)
            Plotly.newPlot('chart-dsfolder-types', [{
                type: 'bar', orientation: 'h',
                y:    @json($typeLabels),
                x:    @json($typeValues),
                marker: {color: '#0d6efd'},
                hovertemplate: '%{y}: %{x:,} file(s)<extra></extra>',
                text: @json(array_map(fn($v) => (string)$v, $typeValues)),
                textposition: 'inside', insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 160, r: 20},
                xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif

            @if(count($topBySize) > 0)
            Plotly.newPlot('chart-dsfolder-sizes', [{
                type: 'bar', orientation: 'h',
                y:    @json($topNames),
                x:    @json($topSizes),
                marker: {color: '#198754'},
                text: @json($topHuman),
                textposition: 'inside', insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
                hovertemplate: '%{y}<br>%{text}<extra></extra>',
            }], base({
                margin: {t: 5, b: 30, l: 160, r: 20},
                xaxis: {
                    tickformat: '.3s', tickfont: {size: 9}, gridcolor: '#dee2e6',
                    title: {text: 'bytes', font: {size: 10}}
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif
        })();
    </script>
@endpush

<x-show-dataset-dir-path :dataset="$dataset" :file="$directory"/>
<br/>
@if ($directory->path !== '/')
    <a href="{{route('public.datasets.folders.show', [$dataset, $directory->directory_id])}}" class="mb-3">
        <i class="fa-fw fas fa-arrow-alt-circle-up me-2"></i>Go up one level
    </a>
    <br>
    <br>
@endif

<table id="files" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Size</th>
        <th>Real Size</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($files as $file)
        <tr>
            <td>
                @if($file->isDir())
                    <a class="no-underline" href="{{route('public.datasets.folders.show', [$dataset, $file])}}">
                        <i class="fa-fw fas fa-folder me-2"></i> {{$file->name}}
                    </a>
                @else
                    <a class="no-underline" href="{{route('public.datasets.files.show', [$dataset, $file])}}">
                        <i class="fa-fw fas fa-file me-2"></i> {{$file->name}}
                    </a>
                @endif
            </td>
            <td>{{$file->mime_type}}</td>
            @if($file->isDir())
                <td></td>
            @else
                <td>{{$file->toHumanBytes()}}</td>
            @endif
            <td>{{$file->size}}</td>
            <td>
                @if($file->isImage())
                    @if($file->size < 94371840)
                        {{-- Change next two routes to public.datasets.files.display once that is implemented --}}
                        <a href="{{route('public.datasets.files.show', [$dataset, $file])}}">

                            <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
                                 style="width: 12rem">
                        </a>
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<x-display-markdown-file :file="$readme"></x-display-markdown-file>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#files').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false},
                ]
            });
        });
    </script>
@endpush
