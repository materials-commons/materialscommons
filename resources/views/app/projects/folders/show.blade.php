@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @php
        $dirAnalyticsKey = 'mc_proj_' . $project->id . '_dir_' . $directory->id . '_analytics';

        $fileCount   = 0;
        $dirCount    = 0;
        $imageCount  = 0;
        $totalSize   = 0;
        $typeCounts  = [];
        $topBySize   = [];
        $monthCounts = [];

        foreach ($files as $f) {
            if ($f->isDir()) {
                $dirCount++;
            } else {
                $fileCount++;
                $totalSize += (int)$f->size;
                if ($f->isImage()) { $imageCount++; }

                $typeDesc = $f->mimeTypeToDescriptionForDisplay($f);
                $typeCounts[$typeDesc] = ($typeCounts[$typeDesc] ?? 0) + 1;

                $topBySize[] = [
                    'name'  => strlen($f->name) > 35 ? substr($f->name, 0, 33) . '…' : $f->name,
                    'size'  => (int)$f->size,
                    'human' => $f->toHumanBytes(),
                ];

                $monthKey = $f->created_at->format('Y-m');
                $monthCounts[$monthKey] = ($monthCounts[$monthKey] ?? 0) + 1;
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

        ksort($monthCounts);
        $monthLabels = array_keys($monthCounts);
        $monthValues = array_values($monthCounts);
    @endphp
    <x-show-dir-path :project="$project" :dir="$directory"/>
    <x-projects.folders.controls :project="$project" :directory="$directory" :scripts="$scripts"
                                 arg="{{$arg}}" :destdir="$destDir" :destproj="$destProj->id"/>
    @if ($directory->path == '/')
        <span class="float-start action-link me-4">
                    <i class="fa-fw fas fa-filter me-2"></i>
                    Filter:
                </span>
        {{--                <a class="float-start action-link me-4" href="#">--}}
        {{--                    <i class="fa-fw fas fa-calendar me-2"></i>--}}
        {{--                    By Date--}}
        {{--                </a>--}}

        <a class="float-start action-link" href="{{route('projects.folders.filter.by-user', [$project])}}">
            <i class="fa-fw fas fa-user-friends me-2"></i>
            By User
        </a>

        <br>
        <br>
    @endif

    @if ($directory->path !== '/')
        <a href="{{route('projects.folders.show', [$project, $directory->directory_id, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}"
           class="mb-3">
            <i class="fa-fw fas fa-arrow-alt-circle-up me-2"></i>Go up one level
        </a>
        <br>
        <br>
    @endif

    @if(isInBeta('dashboard-charts'))
        {{-- ══ KPI strip — always visible ═══════════════════════════════════════════ --}}
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

        {{-- ══ Analytics — collapsible, default CLOSED ═══════════════════════════════ --}}
        @if($fileCount > 0)
            <div class="d-flex align-items-center mb-2">
                <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                        type="button"
                        id="dir-analytics-toggle"
                        data-bs-toggle="collapse"
                        data-bs-target="#dir-analytics"
                        aria-expanded="false"
                        aria-controls="dir-analytics">
                    <i class="fas fa-chevron-right fa-fw" id="dir-analytics-chevron"
                       style="transition:transform .2s; font-size:.75rem;"></i>
                    <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
                </button>
                <hr class="flex-grow-1 ms-3 my-0 opacity-25">
            </div>
            <div class="collapse mb-3" id="dir-analytics">
                <div class="row g-3">

                    {{-- Chart 1: File types --}}
                    @if(count($typeLabels) > 0)
                        <div class="col-12 col-md-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-file-alt me-1"></i> File Types
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        Distribution of file types in this folder
                                        @if(count($typeCounts) > 15)
                                            , showing top 15
                                        @endif
                                    </p>
                                    <div id="chart-dir-types"
                                         style="height:{{ min(60 + count($typeLabels) * 26, 420) }}px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chart 2: Largest files --}}
                    @if(count($topBySize) > 0)
                        <div class="col-12 col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-weight me-1"></i> Largest Files
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        Top {{ count($topBySize) }} files by size
                                    </p>
                                    <div id="chart-dir-sizes"
                                         style="height:{{ min(60 + count($topBySize) * 26, 320) }}px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chart 3: Upload timeline --}}
                    {{--                @if(count($monthLabels) > 1)--}}
                    <div class="col-12 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Upload Timeline
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Files uploaded per month
                                </p>
                                <div id="chart-dir-months" style="height:220px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <form method="post" action="{{route('projects.folders.move.update', [$project, $directory])}}"
          id="move-copy-files">
        @csrf

        @if($arg == 'move-copy')
            <div class="mb-3">
                <label for="project">Destination Project</label>
                <select name="project" id="select-project" title="Current project">
                    @foreach($projects as $p)
                        <option data-token="{{$p->id}}"
                                value="{{$p->id}}" @selected($p->id == $destProj->id)>
                            @if($p->id == $project->id)
                                This Project ({{$p->name}})
                            @else
                                {{$p->name}}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="directories">Destination</label>
                <select name="directory" id="select-directory" title="Select directory">
                    @foreach($dirsInProject as $dir)
                        <option data-token="{{$dir->id}}"
                                value="{{$dir->id}}" @selected($dir->id == $destDir)>
                            {{$dir->path}}
                        </option>
                    @endforeach
                </select>
                <div class="float-end mt-3">
                    <a href="{{route('projects.folders.show', [$project, $directory])}}"
                       class="btn btn-info me-3">
                        Done
                    </a>

                    <a class="btn btn-success" onclick="moveFiles()"
                       href="#">
                        Move Selected
                    </a>

                    <a class="btn btn-success" onclick="copyFiles()"
                       href="#">
                        Copy Selected
                    </a>
                </div>
            </div>
            <br/>
            <br/>
            <br/>
        @endif

        <table id="files" class="table table-hover hide-datatable" style="width:100%">
            <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Real Size</th>
                <th>Last Updated</th>
                <th>Real Updated</th>
                <th>Thumbnail</th>
                @if($arg == 'move-copy')
                    <th>Select</th>
                @endif
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr>
                    <td>
                        @if($file->isDir())
                            <a class="no-underline"
                               href="{{route('projects.folders.show', [$project, $file, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}">
                                <i class="fa-fw fas fa-folder me-2"></i>{{$file->name}}
                            </a>
                        @elseif($file->mime_type == 'url')
                            <a class="no-underline" href="{{$file->url}}" target="_blank">
                                <i class="fa-fw fas fa-link me-2"></i> {{$file->name}}
                            </a>
                        @else
                            <a class="no-underline"
                               href="{{route('projects.files.show', [$project, $file])}}">
                                <i class="fa-fw fas fa-file me-2"></i>
                                <x-health.files.health-status-badge-small :file="$file"/> {{$file->name}}
                            </a>
                        @endif
                    </td>
                    <td>{{$file->mimeTypeToDescriptionForDisplay($file)}}</td>
                    @if($file->isDir())
                        <td></td>
                    @else
                        <td>{{$file->toHumanBytes()}}</td>
                    @endif
                    <td>{{$file->size}}</td>
                    <td>{{$file->created_at->diffForHumans()}}</td>
                    <td>{{$file->created_at}}</td>
                    <td>
                        @if($file->isImage())
                            <a href="{{route('projects.files.display', [$project, $file])}}">
                                <img src="{{route('projects.files.display.thumbnail', [$project, $file])}}"
                                     style="width: 12rem">
                            </a>
                        @endif
                    </td>
                    @if($arg == 'move-copy')
                        <td>
                            <input type="checkbox" name="ids[]" value="{{$file->id}}">
                        </td>
                    @endif
                    <td>
                        @if($file->isDir())
                            <a class="action-link" data-bs-toggle="tooltip" title="Rename directory."
                               href="{{route('projects.folders.rename', [$project, $file, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>

                            <a class="action-link" data-bs-toggle="tooltip" title="Delete directory."
                               href="{{route('projects.folders.delete', [$project, $file, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                        @else
                            <a class="action-link" data-bs-toggle="tooltip" title="Rename file."
                               href="{{route('projects.files.rename', [$project, $file])}}">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>
                            <a class="action-link" data-bs-toggle="tooltip" title="Delete file."
                               href="{{route('projects.files.destroy', [$project, $file, 'destproj' => $destProj->id, 'destdir' => $destDir, 'arg' => $arg])}}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>
    <x-display-markdown-file :file="$readme"></x-display-markdown-file>

    {{--    @include('app.dialogs._copy-choose-project-dialog')--}}

    @if($scripts->count() != 0)
        @include('app.dialogs._select-script-dialog')
    @endif

    @push('scripts')
        <script>
            // document.addEventListener('livewire:navigating', () => {
            //     $('#files').DataTable().destroy();
            // }, {once: true});

            function moveFiles() {
                let choosenProjectId = $('#select-project').val();
                let destDirId = $('#select-directory').val();
                let moveRoute = route('projects.folders.move.update', {
                    'project': {{$project->id}},
                    'folder': {{$directory->id}},
                    'destproj': choosenProjectId,
                    'destdir': destDirId,
                    'arg': 'move-copy',
                });
                let form = document.getElementById('move-copy-files');
                form.action = moveRoute;
                form.submit();
            }

            function copyFiles() {
                let choosenProjectId = $('#select-project').val();
                let destDirId = $('#select-directory').val();
                let copyRoute = route('projects.folders.copy-to', {
                    'project': {{$project->id}},
                    'folder': {{$directory->id}},
                    'destproj': choosenProjectId,
                    'destdir': destDirId,
                    'arg': 'move-copy',
                });
                let form = document.getElementById('move-copy-files');
                form.action = copyRoute;
                form.submit();
            }

            $(document).ready(() => {

                if (document.getElementById('select-project')) {
                    new TomSelect("#select-project", {
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                }

                if (document.getElementById('select-directory')) {
                    new TomSelect("#select-directory", {
                        sortField: {
                            field: "text",
                            direction: "asc"
                        },
                    });
                }

                $('#files').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                        {orderData: [5], targets: [4]},
                        {targets: [5], visible: false},
                    ],
                    initComplete: function () {
                        $('#files').show();
                    }
                });

                $('#select-project').on('change', function () {
                    let chosenProjectId = $(this).val();
                    let destDirId = $('#select-directory').val();
                    window.location.href = route('projects.folders.show', {
                        'project': {{$project->id}},
                        'folder': {{$directory->id}},
                        'destproj': chosenProjectId,
                        'destdir': destDirId,
                        'arg': 'move-copy',
                    });
                });

                $('#select-directory').on('change', function () {
                    let destDirId = $(this).val();
                    let chosenProjectId = $('#select-project').val();
                    window.location.href = route('projects.folders.show', {
                        'project': {{$project->id}},
                        'folder': {{$directory->id}},
                        'destproj': chosenProjectId,
                        'destdir': destDirId,
                        'arg': 'move-copy',
                    });
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = '{{ $dirAnalyticsKey }}';
                const panel = document.getElementById('dir-analytics');
                const toggle = document.getElementById('dir-analytics-toggle');
                const chevron = document.getElementById('dir-analytics-chevron');

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
                    paper_bgcolor: 'transparent',
                    plot_bgcolor: 'transparent',
                    font: {family: 'inherit', size: 11},
                    showlegend: false,
                }, extra);

                @if(count($typeLabels) > 0)
                Plotly.newPlot('chart-dir-types', [{
                    type: 'bar',
                    orientation: 'h',
                    y:    @json($typeLabels),
                    x:    @json($typeValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x:,} file(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $typeValues)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 150, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if(count($topBySize) > 0)
                Plotly.newPlot('chart-dir-sizes', [{
                    type: 'bar',
                    orientation: 'h',
                    y:    @json($topNames),
                    x:    @json($topSizes),
                    marker: {color: '#198754'},
                    text: @json($topHuman),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                    hovertemplate: '%{y}<br>%{text}<extra></extra>',
                }], base({
                    margin: {t: 5, b: 30, l: 150, r: 20},
                    xaxis: {
                        tickformat: '.3s',
                        tickfont: {size: 9},
                        gridcolor: '#dee2e6',
                        title: {text: 'bytes', font: {size: 10}},
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                {{--                @if(count($monthLabels) > 1)--}}
                Plotly.newPlot('chart-dir-months', [{
                    type: 'bar',
                    x:    @json($monthLabels),
                    y:    @json($monthValues),
                    marker: {color: '#6f42c1'},
                    hovertemplate: '%{x}: %{y:,} file(s)<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 40, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);
                {{--                @endif--}}

            })();
        </script>
    @endpush

@stop
