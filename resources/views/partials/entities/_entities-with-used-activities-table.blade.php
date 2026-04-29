@php
    // Build 0-indexed activities array for safe index access
    $activitiesArr = ($activities instanceof \Illuminate\Support\Collection)
        ? $activities->values()->all()
        : array_values((array) $activities);

    // Eager-load entity counts (measurements=attributes, files, states) — single batch query each
    if ($entities instanceof \Illuminate\Database\Eloquent\Collection && $entities->isNotEmpty()) {
        $entities->loadCount(['attributes', 'files', 'entityStates']);
    }

    // Fetch per-activity metadata (attributes + files) in two batch queries
    $activityIds = collect($activitiesArr)
        ->map(fn($a) => is_array($a) ? ($a['id'] ?? null) : ($a->id ?? null))
        ->filter()
        ->values()
        ->all();

    // attributes = process parameters (direct on activity)
    // measurements = attributes on output entityStates (direction=out)
    // files = files attached to the activity
    $activityMeta = \App\Models\Activity::whereIn('id', $activityIds)
        ->withCount(['attributes', 'files'])
        ->with([
            'entityStates' => fn($q) => $q->wherePivot('direction', 'out')->withCount('attributes'),
        ])
        ->get()
        ->keyBy('id');
@endphp

@if(Request::routeIs('projects.entities.*'))
    <x-mql.query-builder :category="$category" :activities="$activities" :project="$project"/>
@endif

{{-- Summary Sidebar --}}
<div id="entitySidebar" style="display:none; position:fixed; right:0; top:0; bottom:0; width:340px;
     background:white; border-left:1px solid #dee2e6; z-index:1055; overflow-y:auto;
     box-shadow:-4px 0 16px rgba(0,0,0,.12);">
    <div
        style="padding:14px 16px; border-bottom:1px solid #dee2e6; display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <div style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em; color:#6c757d;">Sample</div>
            <div id="entitySidebarTitle" style="font-weight:700; font-size:.95rem; margin-top:2px;"></div>
        </div>
        <button id="entitySidebarClose"
                style="background:none; border:none; cursor:pointer; font-size:1.4rem; line-height:1; color:#6c757d; padding:0 0 0 8px;"
                title="Close">&times;
        </button>
    </div>
    <div id="entitySidebarBody" style="padding:12px;"></div>
</div>

{{--<livewire:entities.vertical-flow-view :entities="$entities" :activities="$activities"--}}
{{--                                      used-activities="$usedActivities"/>--}}

<div id="table-container" style="display: none">
    <table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
        <thead class="table-light">
        <tr>
            <th>_name_sort</th>
            <th>Name</th>
            <th title="Number of processes this sample participates in"># Proc</th>
            @if(isset($showExperiment) && $showExperiment)
                <th>Study</th>
            @endif
            @foreach($activitiesArr as $activity)
                <th>{{ is_array($activity) ? $activity['name'] : $activity->name }}</th>
            @endforeach
        </tr>
        </thead>

        <tbody>
        @php
            $fromExperiment = (isset($showExperiment) && $showExperiment) ? 'true' : 'false';
        @endphp
        @foreach($entities as $entity)
            @php
                $processCount = 0;
                $entityProcesses = [];
                if (isset($usedActivities[$entity->id])) {
                    foreach ($usedActivities[$entity->id] as $idx => $isUsed) {
                        if ($isUsed && isset($activitiesArr[$idx])) {
                            $processCount++;
                            $act   = $activitiesArr[$idx];
                            $actId = is_array($act) ? ($act['id'] ?? null) : ($act->id ?? null);
                            $meta  = $actId ? $activityMeta->get($actId) : null;
                            $measurements = $meta
                                ? $meta->entityStates->sum('attributes_count')
                                : 0;
                            $entityProcesses[] = [
                                'name'         => is_array($act) ? $act['name'] : $act->name,
                                'attributes'   => $meta ? $meta->attributes_count : 0,
                                'measurements' => $measurements,
                                'files'        => $meta ? $meta->files_count : 0,
                            ];
                        }
                    }
                }
                $entityJson = json_encode([
                    'name'      => $entity->name,
                    'description' => $entity->description ?? null,
                    'files'     => $entity->files_count ?? 0,
                    'states'    => $entity->entity_states_count ?? 0,
                    'processes' => $entityProcesses,
                ]);
            @endphp
            <tr data-entity="{{ $entityJson }}">
                <td>{{ $entity->name }}</td>
                <td>
                    @if(isset($experiment))
                        @if($category == "experimental")
                            <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                        @else
                            <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                        @endif
                    @else
                        @if($category == "experimental")
                            @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                                <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                            @else
                                <a href="{{route('projects.entities.show-spread', [$project, $entity, 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                            @endif
                        @else
                            @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                                <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                            @else
                                <a href="{{route('projects.computations.entities.show-spread', [$project, $entity, 'fromExperiment' => $fromExperiment])}}">{{$entity->name}}</a>
                            @endif
                        @endif
                    @endif
                </td>
                <td>{{ $processCount }}</td>
                @if(isset($showExperiment) && $showExperiment)
                    <td>
                        @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                            {{ $entity->experiments[0]->name }}
                        @endif
                    </td>
                @endif
                @if(isset($usedActivities[$entity->id]))
                    @foreach($usedActivities[$entity->id] as $isUsed)
                        @if($isUsed)
                            <td class="text-center">X</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        #entities-with-used-activities tbody tr {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#entities-with-used-activities').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            const dt = $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                scrollX: true,
                fixedHeader: {
                    header: true,
                    headerOffset: 46,
                },
                fixedColumns: {
                    start: 1
                },
                columnDefs: [
                    {targets: [0], visible: false},
                    {targets: [2], type: 'num', className: 'text-center fw-semibold'},
                ],
                initComplete: function () {
                    $('#table-container').show();
                }
            });

            // Row highlight + sidebar
            // Use dt.row(this).node() so FixedColumns clones resolve to the original row
            const sidebar      = document.getElementById('entitySidebar');
            const sidebarTitle = document.getElementById('entitySidebarTitle');
            const sidebarBody  = document.getElementById('entitySidebarBody');

            document.getElementById('entitySidebarClose').addEventListener('click', () => {
                sidebar.style.display = 'none';
            });

            let _activeRowNode = null;

            function _statCard(label, value, color) {
                return `<div style="text-align:center;padding:8px 4px;border:1px solid #dee2e6;border-radius:6px;">
                    <div style="font-weight:700;font-size:1.05rem;color:${color};">${value}</div>
                    <div style="font-size:.62rem;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">${label}</div>
                </div>`;
            }

            $('#entities-with-used-activities tbody').on('click', 'tr', function () {
                const originalRow = dt.row(this).node();

                // Same row clicked while open → close
                if (sidebar.style.display === 'block' && originalRow === _activeRowNode) {
                    sidebar.style.display = 'none';
                    _activeRowNode = null;
                    return;
                }

                _activeRowNode = originalRow;
                let entity = $(originalRow).data('entity');
                if (typeof entity === 'string') {
                    try { entity = JSON.parse(entity); } catch (e) { entity = {}; }
                }
                entity = entity || {};

                sidebarTitle.textContent = entity.name || '';

                // Stats strip — files and states at entity level
                let html = `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:14px;">
                    ${_statCard('Files', entity.files ?? 0, '#198754')}
                    ${_statCard('States', entity.states ?? 0, '#6c757d')}
                </div>`;

                // Description
                if (entity.description) {
                    html += `<div style="font-size:.8rem;color:#495057;margin-bottom:12px;padding:8px;background:#f8f9fa;border-radius:4px;border-left:3px solid #dee2e6;">${entity.description}</div>`;
                }

                // Processes
                const processes = entity.processes || [];
                html += `<div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;margin-bottom:8px;font-weight:600;border-bottom:1px solid #dee2e6;padding-bottom:4px;">
                    Processes (${processes.length})
                </div>`;

                if (processes.length === 0) {
                    html += `<p style="color:#6c757d;font-style:italic;font-size:.85rem;">No processes recorded.</p>`;
                } else {
                    html += processes.map((p, i) => `
                        <div style="margin-bottom:8px;padding:8px 10px;border:1px solid #dee2e6;border-radius:6px;">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                                <span style="background:#6f42c1;color:white;border-radius:50px;min-width:20px;text-align:center;font-size:.65rem;padding:1px 4px;flex-shrink:0;">${i + 1}</span>
                                <span style="font-weight:600;font-size:.85rem;">${p.name}</span>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:4px;padding-left:26px;">
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div style="font-weight:700;font-size:.9rem;color:#0d6efd;">${p.attributes}</div>
                                    <div>Attributes</div>
                                </div>
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div style="font-weight:700;font-size:.9rem;color:#fd7e14;">${p.measurements}</div>
                                    <div>Measurements</div>
                                </div>
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div style="font-weight:700;font-size:.9rem;color:#198754;">${p.files}</div>
                                    <div>Files</div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }

                sidebarBody.innerHTML = html;
                sidebar.style.display = 'block';
            });
        });

        htmx.on('htmx:after-settle', (evt) => {
            if (evt.target.id === "mql-query") {
                mcutil.autosizeTextareas();
            }
        });

        function initMQLBuilder() {
            return {
                showBuilder: false,
                showSavedQueries: false,
                toggleShowBuilder() {
                    this.showBuilder = !this.showBuilder;
                    if (this.showBuilder) {
                        mcutil.autosizeTextareas();
                    }
                },
                toggleShowSavedQueries() {
                    this.showSavedQueries = !this.showSavedQueries;
                }
            };
        }
    </script>
@endpush
