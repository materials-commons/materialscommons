<table id="activities" class="bootstrap-table bootstrap-table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Process</th>
        <th>Description</th>
        <th>Updated</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entity->activities as $activity)
        <tr>
            <td>
                <a href="{{$showActivityRoute($activity)}}">{{$activity->name}}</a>
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
                    {targets: [3], visible: false, searchable: false}
                ]
            });
        });
    </script>
@endpush