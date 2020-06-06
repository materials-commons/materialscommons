<table id="entities-with-used-activities" class="table table-hover">
    <thead>
    <th>Name</th>
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
            @foreach($usedActivities[$entity->id] as $used)
                @if($used)
                    <td>{{$entity->name}} ({{$used}})</td>
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