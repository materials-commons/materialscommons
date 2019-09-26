<table id="activities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Process</th>
        <th>Description</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entity->activities as $activity)
        <tr>
            <td>
                <a href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
            </td>
            <td>{{$activity->description}}</td>
            <td>{{$activity->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#activities').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
