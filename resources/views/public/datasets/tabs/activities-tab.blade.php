<table id="activities" class="table table-hover" width="100%">
    <thead>
    <th>Process</th>
    <th>Description</th>
    <th>Updated</th>
    </thead>
    <tbody>
    @foreach($dataset->activities as $activity)
        <tr>
            <td>
                <a href="#">{{$activity->name}}</a>
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
