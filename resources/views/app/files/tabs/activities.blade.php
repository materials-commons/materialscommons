<table id="activities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Process</th>
        <th>Summary</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($file->activities as $activity)
        <tr>
            <td>
                @isset($project)
                    <a href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
                @endisset
            </td>
            <td>{{$activity->summary}}</td>
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
