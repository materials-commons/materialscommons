<table id="activities" class="table table-hover" width="100%">
    <thead>
    <th>Process</th>
    <th>Description</th>
    <th>Updated</th>
    <th>Date</th>
    </thead>
    <tbody>
    @foreach($dataset->activities as $activity)
        <tr>
            <td>
                <a href="{{route('public.datasets.activities.show', [$dataset, $activity])}}">{{$activity->name}}</a>
            </td>
            <td>{{$activity->description}}</td>
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
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
