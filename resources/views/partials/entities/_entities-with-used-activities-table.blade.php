@if(isInBeta() && Request::routeIs('projects.entities.*'))
    <div id="mql-query-builder" x-data="initMQLBuilder()">
        <div id="open-query-builder" x-show="!showBuilder">
            <a href="#" @click="toggleShowBuilder()">Open Query Builder</a>
            <p>
                Query for matching samples by process type and attributes.
            </p>
        </div>
        <div id="query-builder" style="display: none" x-show="showBuilder">
            @include('partials.mql._query-builder')
        </div>
    </div>
    <br>
@endif
<table id="entities-with-used-activities" class="table table-hover" style="width: 100%">
    <thead>
    <th>Name</th>
    @if(isset($showExperiment))
        <th>Experiment</th>
    @endif
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>
                <a href="{{route('projects.entities.show-spread', [$project, $entity])}}">
                    {{$entity->name}}
                </a>
            </td>
            @if(isset($showExperiment))
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

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                scrollX: true,
                stateSave: true,
            });
        });
        htmx.on('htmx:after-settle', (evt) => {
            if (evt.target.id == "mql-query") {
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
                    console.log('toggleShowSavedQueries');
                    this.showSavedQueries = !this.showSavedQueries;
                }
            };
        }
    </script>
@endpush