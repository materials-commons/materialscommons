@extends('layouts.app')

@section('pageTitle', "{$project->name} - Sheets")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @php
        $sheetsKey    = 'mc_proj_' . $project->id . '_sheets_analytics';
        $total        = count($sheets);
        $googleCount  = 0;
        $csvCount     = 0;
        $excelCount   = 0;
        $dirCounts    = [];

        foreach ($sheets as $sheet) {
            if (isset($sheet->url)) {
                $googleCount++;
            } elseif ($sheet->mime_type === 'text/csv') {
                $csvCount++;
                $dp = $sheet->directory->path ?? '/';
                $dirCounts[$dp] = ($dirCounts[$dp] ?? 0) + 1;
            } else {
                $excelCount++;
                $dp = $sheet->directory->path ?? '/';
                $dirCounts[$dp] = ($dirCounts[$dp] ?? 0) + 1;
            }
        }

        $typeLabels = [];
        $typeValues = [];
        if ($googleCount) { $typeLabels[] = 'Google Sheet'; $typeValues[] = $googleCount; }
        if ($csvCount)    { $typeLabels[] = 'CSV';          $typeValues[] = $csvCount; }
        if ($excelCount)  { $typeLabels[] = 'Excel';        $typeValues[] = $excelCount; }

        arsort($dirCounts);
        $dirLabels = array_keys(array_slice($dirCounts, 0, 15, true));
        $dirValues = array_values(array_slice($dirCounts, 0, 15, true));
    @endphp

    <h4>Sheets</h4>
    <div class="float-end">
        <a data-bs-toggle="modal" href="#add-google-sheet-modal"
           class="btn btn-success">
            <i class="fa fas fa-plus me-2"></i>Add Google Sheet
        </a>
    </div>
    @include('app.projects.files._new-sheet-modal')
    <br>
    <br>

    {{-- ══ KPI strip — always visible ═══════════════════════════════════════════ --}}
    <x-collapsible-section title="KPI" id="files-kpi" storage-key="proj_sheets_kpi">
        <div class="row g-2 mb-3">
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Total Sheets</div>
                    <div class="fw-bold fs-5 text-primary">{{ number_format($total) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">in this project</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Google Sheets</div>
                    <div class="fw-bold fs-5 text-success">{{ number_format($googleCount) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">linked</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">CSV Files</div>
                    <div class="fw-bold fs-5 text-info">{{ number_format($csvCount) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">comma-separated</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Excel Files</div>
                    <div class="fw-bold fs-5 text-warning">{{ number_format($excelCount) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">spreadsheets</div>
                </div>
            </div>
        </div>
    </x-collapsible-section>
    {{-- ══ Analytics — collapsible, default CLOSED ═══════════════════════════════ --}}
    @if($total > 0)
        <x-collapsible-section title="Analytics"
                               id="sheets-analytics"
                               :resize-plotly="true"
                               storage-key="proj_sheets_analytics">
            <div class="row g-3">

                {{-- Chart 1: Type breakdown --}}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-table me-1"></i> Sheet Types
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Breakdown by format
                            </p>
                            <div id="chart-sheets-types" style="height:200px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Chart 2: By directory --}}
                @if(count($dirLabels) > 0)
                    <div class="col-12 col-md-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-folder-open me-1"></i> Sheets by Directory
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Which folders contain the most sheet files
                                    @if(count($dirCounts) > 15)
                                        , showing top 15
                                    @endif
                                </p>
                                <div id="chart-sheets-dirs"
                                     style="height:{{ min(60 + count($dirLabels) * 26, 380) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </x-collapsible-section>
    @endif {{-- total > 0 --}}


    <table id="sheets" class="table table-hover" style="width:100%">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Directory</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sheets as $sheet)
            <tr>
                @if(isset($sheet->url))
                    <td>
                        <a href="{{$sheet->url}}" class="no-underline" target="_blank">{{$sheet->title}}</a>
                    </td>
                    <td>Google Sheet</td>
                    <td></td>
                @else
                    <td>
                        <a href="{{route('projects.files.show', [$sheet->project_id, $sheet->id])}}"
                           class="no-underline">{{App\Helpers\PathHelpers::joinPaths($sheet->directory->path, $sheet->name)}}</a>
                    </td>
                    <td>
                        @if($sheet->mime_type === "text/csv")
                            CSV
                        @else
                            Excel
                        @endif
                    </td>
                    <td>
                        <a href="{{route('projects.folders.show', [$sheet->project_id, $sheet->directory->id])}}"
                           class="no-underline">{{$sheet->directory->path}}</a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#sheets').DataTable().destroy();
            }, {once: true});

            $(document).ready(() => {
                $('#sheets').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    initComplete: function () {
                        $('#sheets thead').addClass('table-light');
                    }
                });
            });
        </script>
    @endpush

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

                // Chart 1: Type breakdown donut
                Plotly.newPlot('chart-sheets-types', [{
                    type: 'pie',
                    hole: 0.5,
                    labels: @json($typeLabels),
                    values: @json($typeValues),
                    marker: {colors: ['#198754', '#0dcaf0', '#ffc107']},
                    textinfo: 'label+value',
                    hoverinfo: 'label+value+percent',
                    textfont: {size: 10},
                }], base({
                    margin: {t: 10, b: 10, l: 10, r: 10},
                }), plotConfig);

                @if(count($dirLabels) > 0)
                // Chart 2: Sheets by directory
                Plotly.newPlot('chart-sheets-dirs', [{
                    type: 'bar',
                    orientation: 'h',
                    y:    @json($dirLabels),
                    x:    @json($dirValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x:,} sheet(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $dirValues)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {type: 'log', tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

            })();
        </script>
    @endpush
@stop
