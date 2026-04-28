<table id="activities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Process</th>
        <th>Summary</th>
        <th>Updated</th>
        <th>Date</th>
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
            <td>{{$activity->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#activities').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
