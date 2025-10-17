<div>
    <a class="btn btn-success float-end ms-3"
       data-toggle="modal" href="#create-transfer-request-modal">Create Transfer Request</a>
    @include('app.admin.partials._create-transfer-request-modal')
    <a class="btn btn-success float-end" href="{{route('admin.dashboard.mcfs.show-log-viewer')}}"><i
                class="fa fa-fw fa-file-alt me-2"></i>Log Viewer</a>
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
        <th>Last Activity At</th>
        <th>Activity Count</th>
        <th>Files Written</th>
        <th>Annotations</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transferRequests as $tr)
        @if($tr->transfer_request_files_count > 0 && !$transferRequestsStatus[$tr->uuid]->activityFound)
            <tr class="table-danger">
        @elseif(!$transferRequestsStatus[$tr->uuid]->activityFound)
            <tr class="table-warning">
        @else
            <tr>
                @endif
                <td>
                    <a href="{{route('admin.dashboard.mcfs.transfer-requests.show', [$tr])}}">{{$tr->uuid}}</a>
                </td>
                <td><a href="{{route('projects.show', [$tr->project])}}">{{$tr->project->name}}</a></td>
                <td>{{$tr->owner->name}}</td>
                @if(is_null($tr->globusTransfer))
                    <td></td>
                @else
                    <td>
                        <a href="{{$tr->globusTransfer->globus_url}}" target="_blank">
                            <i class="fa fa-fw fa-external-link-alt ms-2"></i>Globus
                        </a>
                    </td>
                @endif
                <td>{{$tr->created_at->diffForHumans()}}</td>
                @if($transferRequestsStatus->has($tr->uuid))
                    @if($transferRequestsStatus[$tr->uuid]->activityFound)
                        <td>Yes</td>
                    @else
                        <td>No</td>
                    @endif
                    @if($transferRequestsStatus[$tr->uuid]->lastActivityTime == "unknown")
                        <td>{{$transferRequestsStatus[$tr->uuid]->lastActivityTime}}</td>
                    @else
                        <td>{{Carbon\Carbon::parse($transferRequestsStatus[$tr->uuid]->lastActivityTime)->diffForHumans()}}</td>
                    @endif
                    <td>{{number_format($transferRequestsStatus[$tr->uuid]->activityCount)}}</td>
                @else
                    <td>No activity status</td>
                    <td></td>
                    <td></td>
                @endif
                <td>{{$tr->transfer_request_files_count}}</td>
                <td>
                    <x-annotations.notes-symbol/>
                </td>
            </tr>
            @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#mcfs-index').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            $('#mcfs-index').DataTable({
                pageLength: 100,
            });
        });
    </script>
@endpush
