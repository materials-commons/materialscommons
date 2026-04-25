@php
    $verCount   = $previousVersions->count();
    $activeVer  = $previousVersions->firstWhere('current', true);
    $activeSz   = $activeVer ? $activeVer->toHumanBytes() : $file->toHumanBytes();

    // Build version size series (oldest → newest)
    $sorted     = $previousVersions->sortBy('created_at');
    $verLabels  = $sorted->map(fn($v) => $v->created_at->format('Y-m-d') . ($v->current ? ' ★' : ''))->values()->toArray();
    $verSizes   = $sorted->pluck('size')->map(fn($s) => (int)$s)->values()->toArray();
    $verHuman   = $sorted->map(fn($v) => $v->toHumanBytes())->values()->toArray();
    $verColors  = $sorted->map(fn($v) => $v->current ? '#0d6efd' : '#adb5bd')->values()->toArray();
@endphp

{{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
@if(isInBeta('dashboard-charts'))
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Total Versions</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($verCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">including active</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Active Size</div>
                <div class="fw-bold fs-5 text-success">{{ $activeSz }}</div>
                <div class="text-muted" style="font-size:.65rem;">current version</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">First Upload</div>
                <div class="fw-bold text-muted" style="font-size:.85rem;">
                    {{ $sorted->first()?->created_at->format('M j, Y') ?? '—' }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">
                    {{ $sorted->first()?->created_at->diffForHumans() ?? '' }}
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Latest Upload</div>
                <div class="fw-bold text-muted" style="font-size:.85rem;">
                    {{ $sorted->last()?->created_at->format('M j, Y') ?? '—' }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">
                    {{ $sorted->last()?->created_at->diffForHumans() ?? '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Size history chart ═══════════════════════════════════════════════════════ --}}
    @if($verCount > 1)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-bar me-1"></i> Version Size History
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Size per version — blue bar is the active version
                </p>
                <div id="chart-ver-sizes" style="height:180px;"></div>
            </div>
        </div>
    @endif
@endif

<table id="file-versions" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Uploaded</th>
        <th>Size</th>
        <th>Real Size</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($previousVersions as $filever)
        <tr>
            <td>
                <a href="{{route('projects.files.show', [$project, $filever])}}">{{$filever->name}}</a>
                @if($filever->current)
                    (Active)
                @endif
            </td>
            <td>{{$filever->created_at->diffForHumans()}}</td>
            <td>{{$filever->toHumanBytes()}}</td>
            <td>{{$filever->size}}</td>
            <td>
                @if($filever->isImage())
                    <a href="{{route('projects.files.display', [$project, $filever])}}">

                        <img src="{{route('projects.files.display', [$project, $filever])}}"
                             style="width: 12rem">
                    </a>
                @endif
            </td>
            <td>
                @if(!$filever->current)
                    <a class="action-link" href="{{route('projects.files.set-active', [$project, $filever])}}">
                        <i class="fas fa-history me-2"></i>Set as active version
                    </a>
                    <br>
                @endif
                <a class="action-link"
                   href="{{route('projects.files.download', [$project, $filever])}}">
                    <i class="fas fa-download me-2"></i>Download File
                </a>
                <br>
                <a class="action-link" href="{{route('projects.files.compare', [$project, $file, $filever])}}">
                    <i class="fas fa-columns me-2"></i>Compare
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        @if(isset($verCount) && $verCount > 1)
        (function () {
            Plotly.newPlot('chart-ver-sizes', [{
                type: 'bar',
                x:    @json($verLabels),
                y:    @json($verSizes),
                marker: {color: @json($verColors)},
                text: @json($verHuman),
                textposition: 'outside',
                textfont: {size: 9},
                hovertemplate: '%{x}<br>%{text}<extra></extra>',
                cliponaxis: false,
            }], {
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 10},
                showlegend: false,
                margin: {t: 20, b: 60, l: 50, r: 20},
                xaxis: {tickangle: -35, tickfont: {size: 9}},
                yaxis: {
                    tickformat: '.3s',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'bytes', font: {size: 10}},
                },
            }, {responsive: true, displayModeBar: false});
        })();
        @endif

        $(document).ready(() => {
            $('#file-versions').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
