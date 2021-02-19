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
                <a href="{{route('projects.entities.show', [$project, $entity])}}">
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
            @foreach($usedActivities[$entity->id] as $used)
                @if($used)
                    <td>X</td>
                    {{--                    <td>{{$entity->name}}</td>--}}
                    {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                @else
                    <td></td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                stateSave: true,
                scrollX: true,
            });
        });
    </script>
@endpush