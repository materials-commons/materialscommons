<x-table-container>

            @if(Request::routeIs('projects.entities.*'))
        <x-mql.query-builder :category="$category" :activities="$activities" :project="$project"/>
    @endif

    {{-- Selection Controls --}}
    <div class="mb-3 d-flex align-items-center">
        <button id="viewSelectedBtn" class="btn btn-primary" style="display: none;">
            <i class="fas fa-sitemap mr-1"></i> View Selected Samples Flow
        </button>
        <span id="selectedCount" class="ml-3 text-muted"></span>
    </div>

    {{-- Inline Vertical Flow View (Hidden by default) --}}
    <div id="verticalFlowView" class="vertical-flow-view" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Sample Activity Flow</h5>
            <button id="closeFlowViewBtn" class="btn btn-sm btn-secondary">
                <i class="fas fa-times mr-1"></i> Close
            </button>
        </div>
        <div id="verticalFlowContainer" class="vertical-flow-grid">
            <!-- Dynamically populated -->
        </div>
        <hr class="my-4">
    </div>

    <table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
        <thead>
        <th>
            <input type="checkbox" id="selectAll" title="Select all">
        </th>
        <th>Name1</th>
        <th>Name</th>
        @if(isset($showExperiment) && $showExperiment)
            <th>Study</th>
        @endif
        @foreach($activities as $activity)
            <th>{{$activity->name}}</th>
        @endforeach
        </thead>
        <tbody>
        @php
            if (isset($showExperiment) && $showExperiment) {
                $fromExperiment = 'true';
            } else {
                $fromExperiment = 'false';
            }
        @endphp
        @foreach($entities as $entity)
            <tr data-entity-id="{{$entity->id}}" data-entity-name="{{$entity->name}}">
                <td>
                    <input type="checkbox" class="entity-checkbox" value="{{$entity->id}}">
                </td>
                <td>{{$entity->name}}</td>
                <td>
                    @if(isset($experiment))
                        @if($category == "experimental")
                            <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">
                                {{$entity->name}}
                            </a>
                        @else
                            <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">
                                {{$entity->name}}
                            </a>
                        @endif
                    @else
                        @if($category == "experimental")
                            @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                                <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">
                                    {{$entity->name}}
                                </a>
                            @else
                                <a href="{{route('projects.entities.show-spread', [$project, $entity, 'fromExperiment' => $fromExperiment])}}">
                                    {{$entity->name}}
                                </a>
                            @endif
                        @else
                            @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                                <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => $fromExperiment])}}">
                                    {{$entity->name}}
                                </a>
                            @else
                                <a href="{{route('projects.computations.entities.show-spread', [$project, $entity, 'fromExperiment' => $fromExperiment])}}">
                                    {{$entity->name}}
                                </a>
                            @endif
                        @endif
                    @endif
                </td>
                @if(isset($showExperiment) && $showExperiment)
                    <td>
                        @if(isset($entity->experiments))
                            @if($entity->experiments->count() > 0)
                                {{$entity->experiments[0]->name}}
                            @endif
                        @endif
                    </td>
                @endif
                @if(isset($usedActivities[$entity->id]))
                    @foreach($usedActivities[$entity->id] as $used)
                        @if($used)
                            <td>X</td>
                            {{--                    <td>{{$entity->name}}</td>--}}
                            {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                        @else
                            <td></td>
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</x-table-container>

@push('styles')
    <style>
        /* Vertical Flow View Styles */
        .vertical-flow-view {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .vertical-flow-grid {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(250px, 1fr);
            gap: 30px;
            padding: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        .sample-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 200px;
        }

        .sample-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 1rem;
        }

        .flow-connector {
            width: 3px;
            height: 25px;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            margin: 0;
            position: relative;
        }

        .flow-connector::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #764ba2;
        }

        .activity-box {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            margin: 5px 0;
        }

        .activity-box:hover {
            border-color: #667eea;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .activity-name {
            font-weight: 500;
            color: #2d3748;
            font-size: 0.9rem;
            text-align: center;
        }

        .no-activities {
            color: #a0aec0;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            width: 100%;
        }

        /* Checkbox styling */
        .entity-checkbox {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        #selectAll {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        /* Button styling */
        #viewSelectedBtn {
            transition: all 0.2s ease;
        }

        #viewSelectedBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        #selectedCount {
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Store activities data globally
        const activitiesMap = @json($activities);
        const usedActivitiesData = @json($usedActivities);

        document.addEventListener('livewire:navigating', () => {
            $('#entities-with-used-activities').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                scrollX: true,
                fixedHeader: {
                    header: true,
                    headerOffset: 46,
                },
                columnDefs: [
                    {targets: [1, 2], visible: false},
                ],
            });

            // Vertical Flow View functionality
            let selectedEntities = [];

            // Select all checkbox handler
            $('#selectAll').on('change', function() {
                $('.entity-checkbox').prop('checked', this.checked);
                updateSelectedEntities();
            });

            // Individual checkbox handler
            $('.entity-checkbox').on('change', function() {
                updateSelectedEntities();
            });

            function updateSelectedEntities() {
                selectedEntities = [];
                $('.entity-checkbox:checked').each(function() {
                    const row = $(this).closest('tr');
                    selectedEntities.push({
                        id: row.data('entity-id'),
                        name: row.data('entity-name')
                    });
                });

                if (selectedEntities.length > 0) {
                    $('#viewSelectedBtn').show();
                    $('#selectedCount').text(`${selectedEntities.length} sample(s) selected`);
                } else {
                    $('#viewSelectedBtn').hide();
                    $('#selectedCount').text('');
                }
            }

            // View selected button handler
            $('#viewSelectedBtn').on('click', function() {
                renderVerticalFlow();
                $('#verticalFlowView').slideDown(400);
                $('html, body').animate({
                    scrollTop: $('#verticalFlowView').offset().top - 70
                }, 400);
            });

            // Close flow view button handler
            $('#closeFlowViewBtn').on('click', function() {
                $('#verticalFlowView').slideUp(400);
            });

            function renderVerticalFlow() {
                const container = $('#verticalFlowContainer');
                container.empty();

                selectedEntities.forEach(entity => {
                    const activities = getActivitiesForEntity(entity.id);
                    const column = createSampleColumn(entity.name, activities);
                    container.append(column);
                });
            }

            function getActivitiesForEntity(entityId) {
                const usedArray = usedActivitiesData[entityId];
                if (!usedArray) return [];

                const activities = [];
                usedArray.forEach((isUsed, index) => {
                    if (isUsed && activitiesMap[index]) {
                        activities.push(activitiesMap[index].name);
                    }
                });
                return activities;
            }

            function createSampleColumn(sampleName, activities) {
                const $column = $('<div>', {class: 'sample-column'});

                // Sample header
                const $header = $('<div>', {
                    class: 'sample-header',
                    text: sampleName
                });
                $column.append($header);

                // Activities
                if (activities.length === 0) {
                    const $noActivities = $('<div>', {
                        class: 'no-activities',
                        text: 'No activities'
                    });
                    $column.append($noActivities);
                } else {
                    activities.forEach((activity, index) => {
                        if (index > 0) {
                            const $connector = $('<div>', {class: 'flow-connector'});
                            $column.append($connector);
                        }

                        const $activityBox = $('<div>', {class: 'activity-box'});
                        const $activityName = $('<div>', {
                            class: 'activity-name',
                            text: activity
                        });
                        $activityBox.append($activityName);
                        $column.append($activityBox);
                    });
                }

                return $column;
            }

            // setupHavingProcess();
            // setupHavingActivityAttribute();
            // setupHavingEntityAttribute();
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
