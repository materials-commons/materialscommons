<table id="globus-downloads" class="table table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>Link</th>
        @if($showProject)
            <th>Project</th>
        @endif
        <th>Who</th>
        <th>Updated</th>
        <th>Date</th>
        <th>Status</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($globusDownloads as $download)
        <tr>
            <td>{{$download->name}}</td>
            <td>
                @if ($download->status == \App\Enums\GlobusStatus::Done && $user->id == $download->owner_id)
                    <a href="{{$download->globus_url}}" target="_blank">Goto Globus</a>
                @endif
            </td>
            @if($showProject)
                <td>{{$download->project->name}}</td>
            @endif
            <td>{{$download->owner->name}}</td>
            <td>{{$download->updated_at->diffForHumans()}}</td>
            <td>{{$download->updated_at}}</td>
            <td>
                @if ($download->status == \App\Enums\GlobusStatus::NotStarted)
                    Waiting to start creating project download
                @elseif ($download->status == \App\Enums\GlobusStatus::Loading)
                    Creating project download
                @else
                    Ready to use
                @endif
            </td>
            <td>
                @if ($download->status == \App\Enums\GlobusStatus::Done && $user->id == $download->owner_id)
                    <a href="{{route('projects.globus.downloads.delete', [$download->project, $download])}}"
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-fw fa-trash-alt"></i> delete
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#globus-downloads').DataTable({
                stateSave: true,
                @if($showProject)
                columnDefs: [
                    {orderData: [5], targets: [4]},
                    {targets: [5], visible: false, searchable: false},
                ]
                @else
                columnDefs: [
                    {orderData: [4], targets: [3]},
                    {targets: [4], visible: false, searchable: false},
                ]
                @endif
            });
        });
    </script>
@endpush