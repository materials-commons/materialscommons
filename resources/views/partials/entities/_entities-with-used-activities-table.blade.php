@php
    // Build 0-indexed activities array for safe index access
    $activitiesArr = ($activities instanceof \Illuminate\Support\Collection)
        ? $activities->values()->all()
        : array_values((array) $activities);
@endphp

@if(Request::routeIs('projects.entities.*'))
    <x-mql.query-builder :category="$category" :activities="$activities" :project="$project"/>
@endif

<livewire:entities.entity-sidebar/>

{{--<livewire:entities.vertical-flow-view :entities="$entities" :activities="$activities"--}}
{{--                                      used-activities="$usedActivities"/>--}}

<div id="table-container" style="display: none">
    <table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
        <thead class="table-light">
        <tr>
            <th>_name_sort</th>
            <th>Name</th>
            <th title="Number of processes this sample participates in"># Unique Proc</th>
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
                $entityActivityIds = [];
                if (isset($usedActivities[$entity->id])) {
                    foreach ($usedActivities[$entity->id] as $idx => $isUsed) {
                        if ($isUsed && isset($activitiesArr[$idx])) {
                            $processCount++;
                            $act = $activitiesArr[$idx];
                            $actId = is_array($act) ? ($act['id'] ?? null) : ($act->id ?? null);

                            if ($actId) {
                                $entityActivityIds[] = $actId;
                            }
                        }
                    }
                }
            @endphp
            <tr data-entity-id="{{ $entity->id }}"
                data-activity-ids='@json($entityActivityIds)'
                data-category="{{ $category }}">
                <td>{{ $entity->name }}</td>
                <td>
                    <button type="button"
                            class="btn btn-link btn-sm p-0 me-2 entity-sidebar-toggle"
                            title="View summary for {{ $entity->name }}"
                            aria-label="View summary for {{ $entity->name }}">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
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

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#entities-with-used-activities').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            let experimentId = "{{isset($experiment) ? $experiment->id : 0}}";
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

            $('#entities-with-used-activities tbody').on('click', '.entity-sidebar-toggle', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const originalRow = dt.row($(this).closest('tr')).node();
                const entityId = Number(originalRow.dataset.entityId);
                const category = originalRow.dataset.category || 'experimental';
                let activityIds = [];

                try {
                    activityIds = JSON.parse(originalRow.dataset.activityIds || '[]');
                } catch (e) {
                    activityIds = [];
                }

                Livewire.dispatch('showEntitySidebar', {
                    entityId,
                    experimentId,
                    category,
                });
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
