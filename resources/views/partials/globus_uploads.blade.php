<table id="globus-uploads" class="bootstrap-table bootstrap-table-hover">
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
    @foreach($globusUploads as $upload)
        <tr>
            <td>{{$upload->name}}</td>
            {{--            <td>{{$upload->description}}</td>--}}
            <td>
                @if($upload->status ==  \App\Enums\GlobusStatus::Uploading && $user->id == $upload->owner_id)
                    <a href="{{$upload->globus_url}}" target="_blank">Goto Globus</a>
                @endif
            </td>
            @if($showProject)
                <td>{{$upload->project->name}}</td>
            @endif
            <td>{{$upload->owner->name}}</td>
            <td>{{$upload->updated_at->diffForHumans()}}</td>
            <td>{{$upload->updated_at}}</td>
            <td>
                @if($upload->status == \App\Enums\GlobusStatus::Uploading)
                    Open for Uploads/Uploading files
                @elseif ($upload->status == \App\Enums\GlobusStatus::Loading)
                    Processing files
                @else
                    Waiting to process files
                @endif
            </td>
            <td>
                @if ($upload->status ==  \App\Enums\GlobusStatus::Uploading && $user->id == $upload->owner_id)
                    <a href="{{route('projects.globus.uploads.done', [$upload->project, $upload])}}"
                       class="btn btn-sm btn-success">
                        <i class="fas fa-fw fa-check-circle"></i> done
                    </a>
                    <a href="{{route('projects.globus.uploads.delete', [$upload->project, $upload])}}"
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
            $('#globus-uploads').DataTable({
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