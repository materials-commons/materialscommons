<table id="computations-with-used-activities" class="table table-hover mt-4" style="width: 100%">
    <thead>
    <th>Name1</th>
    <th>Name</th>
    @if(isset($showExperiment))
        <th>Study</th>
    @endif
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>{{$entity->name}}</td>
            <td>
                @if(isset($experiment))
                    <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name)])}}">
                        {{$entity->name}}
                    </a>
                @else
                    @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                        <a href="{{route('projects.experiments.computations.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name)])}}">
                            {{$entity->name}}
                        </a>
                    @else
                        <a href="{{route('projects.computations.entities.show-spread', [$project, $entity])}}">
                            {{$entity->name}}
                        </a>
                    @endif
                @endif
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

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#computations-with-used-activities').DataTable({
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
            });
        </script>
    @endpush

    @script
    <script>
        $wire.on('reload-component', () => {
            $('#computations-with-used-activities').DataTable().destroy();
            setTimeout(() => {
                $('#computations-with-used-activities').DataTable({
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
            });
        });
    </script>
    @endscript
</table>


