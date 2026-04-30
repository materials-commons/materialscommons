@extends('layouts.app')

@section('pageTitle', "{$project->name} - Users")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @php
        $analyticsKey = 'mc_proj_' . $project->id . '_members_analytics';

        $allMembers  = $project->team->members->merge($project->team->admins)->unique('id');
        $adminCount  = $project->team->admins->filter(fn($u) => $u->id !== $project->owner_id)->count();
        $memberCount = $project->team->members->filter(fn($u) => $u->id !== $project->owner_id)->count();

        // Ensure owner is in our working set
        $ownerInSet = $allMembers->contains('id', $project->owner_id);
        $workingSet = $ownerInSet ? $allMembers : $allMembers->push($project->owner);
        $totalCount = $workingSet->unique('id')->count();

        // Files and size contributed per member — one aggregation query
        $memberIds = $workingSet->pluck('id')->unique()->values()->toArray();
        $fileStats = \App\Models\File::where('project_id', $project->id)
            ->active()
            ->where('mime_type', '<>', 'directory')
            ->whereIn('owner_id', $memberIds)
            ->selectRaw('owner_id, count(*) as cnt, sum(size) as total_size')
            ->groupBy('owner_id')
            ->get()
            ->keyBy('owner_id');

        $memberFileData = [];
        foreach ($workingSet->unique('id') as $m) {
            $stats = $fileStats->get($m->id);
            $memberFileData[$m->name] = [
                'files' => $stats ? (int)$stats->cnt : 0,
                'size'  => $stats ? (int)$stats->total_size : 0,
            ];
        }
        uasort($memberFileData, fn($a, $b) => $b['files'] <=> $a['files']);
        $memberFileLabels = array_keys($memberFileData);
        $memberFileCounts = array_column($memberFileData, 'files');
        $memberFileSizes  = array_map(
            fn($b) => $b >= 1073741824
                ? round($b / 1073741824, 2) . ' GB'
                : ($b >= 1048576 ? round($b / 1048576, 2) . ' MB'
                : ($b >= 1024 ? round($b / 1024, 2) . ' KB' : $b . ' B')),
            array_column($memberFileData, 'size')
        );
    @endphp

    <h3 class="text-center">Project Members</h3>
    @if($project->owner->id === auth()->id() || $project->team->admins->contains('id', auth()->id()) || auth()->user()->is_admin)
        <a class="action-link float-end"
           href="{{route('projects.users.edit', [$project])}}">
            <i class="fas fa-plus me-2"></i>Add Users
        </a>
    @endif
    <br/>

    {{-- ══ KPI strip ═══════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Total Members</div>
                <div class="fw-bold fs-5 text-primary">{{ $totalCount }}</div>
                <div class="text-muted" style="font-size:.65rem;">on project</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Admins</div>
                <div class="fw-bold fs-5 text-info">{{ $adminCount }}</div>
                <div class="text-muted" style="font-size:.65rem;">with admin rights</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Members</div>
                <div class="fw-bold fs-5 text-success">{{ $memberCount }}</div>
                <div class="text-muted" style="font-size:.65rem;">regular members</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Owner</div>
                <div class="fw-bold text-warning"
                     style="font-size:.85rem; line-height:1.3;">
                    {{ mb_strlen($project->owner->name) > 18 ? mb_substr($project->owner->name, 0, 16).'…' : $project->owner->name }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">project owner</div>
            </div>
        </div>
    </div>

    {{-- ══ Analytics — collapsible ═════════════════════════════════════════════════ --}}
    @if($totalCount > 0)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="members-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#members-analytics"
                    aria-expanded="false"
                    aria-controls="members-analytics">
                <i class="fas fa-chevron-right fa-fw" id="members-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                    Analytics
                </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="members-analytics">
            <div class="row g-3">

                {{-- Chart 1: Member role donut --}}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-users me-1"></i> Member Roles
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">Breakdown by role</p>
                            <div id="chart-members-roles" style="height:200px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Chart 2: Files contributed per member --}}
                <div class="col-12 col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-file me-1"></i> Files Contributed
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Number of files uploaded per member
                            </p>
                            <div id="chart-members-files"
                                 style="height:{{ min(80 + count($memberFileLabels) * 28, 320) }}px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif

    <br/>

    <table id="users" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Affiliations</th>
            <th>Description</th>
            <th>Type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->team->members->merge($project->team->admins) as $member)
            <tr>
                <td>
                    <a href="{{route('projects.users.show', [$project, $member])}}">
                        {{$member->name}}
                    </a>
                </td>
                <td>{{$member->email}}</td>
                <td>{{$member->affiliations}}</td>
                <td>{{$member->description}}</td>
                <td>
                    @if ($project->owner_id === $member->id)
                        Owner
                    @else
                        {{$project->team->members->contains('id', $member->id) ? 'Member' : 'Admin'}}
                    @endif
                </td>
                <td>
                    @if($project->owner_id != $member->id)
                        @if(auth()->id() == $project->owner_id || $project->team->admins->contains('id', auth()->id()))
                            @if($project->team->members->contains('id', $member->id))
                                <a href="{{route('projects.users.remove', [$project, $member])}}">
                                    <i class="fa fas fa-trash"></i></a>
                                <a href="{{route('projects.users.change-to-admin', [$project, $member])}}"
                                   class="ms-4">
                                    <i class="fa fas fa-fw fa-edit"></i>Make Admin
                                </a>
                            @else
                                <a href="{{route('projects.admins.remove', [$project, $member])}}">
                                    <i class="fa fas fa-trash"></i></a>
                                <a href="{{route('projects.users.change-to-member', [$project, $member])}}"
                                   class="ms-4">
                                    <i class="fa fas fa-fw fa-edit"></i>Make Member
                                </a>
                            @endif
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#users').DataTable().destroy();
            }, {once: true});

            $(document).ready(() => {
                $('#users').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = '{{ $analyticsKey }}';
                const panel = document.getElementById('members-analytics');
                const toggle = document.getElementById('members-analytics-toggle');
                const chevron = document.getElementById('members-analytics-chevron');

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

                // ── 1. Member role donut ───────────────────────────────────────────
                Plotly.newPlot('chart-members-roles', [{
                    type: 'pie',
                    hole: 0.55,
                    labels: ['Owner', 'Admins', 'Members'],
                    values: [1, {{ $adminCount }}, {{ $memberCount }}],
                    marker: {colors: ['#ffc107', '#0dcaf0', '#0d6efd']},
                    textinfo: 'value',
                    hoverinfo: 'label+value+percent',
                    domain: {x: [0, 1], y: [0, 1]},
                }], base({
                    showlegend: true,
                    legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.15, font: {size: 10}},
                    margin: {t: 10, b: 45, l: 5, r: 5},
                }), plotConfig);

                // ── 2. Files contributed per member ──────────────────────────────
                @if(array_sum($memberFileCounts) > 0)
                Plotly.newPlot('chart-members-files', [{
                    type: 'bar',
                    orientation: 'h',
                    y:             @json($memberFileLabels),
                    x:             @json($memberFileCounts),
                    marker: {color: '#0d6efd'},
                    customdata:    @json($memberFileSizes),
                    hovertemplate: '%{y}: %{x:,} file(s) · %{customdata}<extra></extra>',
                    text:          @json(array_map(fn($c) => $c > 0 ? number_format($c) : '', $memberFileCounts)),
                    textposition: 'auto',
                    textfont: {size: 10},
                }], base({
                    margin: {t: 5, b: 30, l: 150, r: 20},
                    xaxis: {
                        type: 'log',
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'files', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @else
                document.getElementById('chart-members-files').innerHTML =
                    '<p class="text-muted text-center pt-4 small">No files uploaded yet</p>';
                @endif

            })();
        </script>
    @endpush
@stop
