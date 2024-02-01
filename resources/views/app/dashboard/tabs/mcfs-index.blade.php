<div>
    <a class="btn btn-success float-right"
       data-toggle="modal" href="#create-transfer-request-modal">Create Transfer Request</a>
    @include('app.dashboard.partials._create-transfer-request-modal')
</div>
<br/>
<br/>
<br/>
<table id="mcfs-index" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Transfer Request</th>
        <th>Project</th>
        <th>User</th>
        <th>Globus Link</th>
        <th>Started On</th>
        <th>Has Activity</th>
        <th># Files</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transferRequests as $tr)
        <tr>
            <td><a href="{{route('mcfs.transfer-requests.show', [$tr])}}">{{$tr->uuid}}</a></td>
            <td><a href="{{route('projects.show', [$tr->project])}}">{{$tr->project->name}}</a></td>
            <td>{{$tr->owner->name}}</td>
            @if(is_null($tr->globusTransfer))
                <td></td>
            @else
                <td><a href="{{$tr->globusTransfer->globus_url}}" target="_blank">Globus Link</a></td>
            @endif
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