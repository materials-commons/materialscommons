<x-table-container>

            @if(Request::routeIs('projects.entities.*'))
        <x-mql.query-builder :category="$category" :activities="$activities" :project="$project"/>
    @endif
    <table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
        <thead>
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
            <tr>
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
@push('scripts')
    <script>
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
                    {targets: [0], visible: false},
                ],
            });

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
