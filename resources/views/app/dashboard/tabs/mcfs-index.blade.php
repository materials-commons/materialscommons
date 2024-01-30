<br/>
<table id="mcfs-index" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Transfer Request</th>
        <th>Project</th>
        <th>User</th>
        <th>Started On</th>
        <th># Files</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transferRequests as $tr)
        <tr>
            <td><a href="">{{$tr->id}}</a></td>
            <td><a href="{{route('projects.show', [$tr->project])}}">{{$tr->project->name}}</a></td>
            <td>{{$tr->owner->name}}</td>
            <td>{{$tr->created_at->diffForHumans()}}</td>
            <td>{{$tr->transfer_request_files_count}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#mcfs-index').DataTable({
                pageLength: 100,
            });
        });
    </script>
@endpush