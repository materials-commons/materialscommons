<br/>
<table id="projects-trash" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Project</th>
        <th>Will be deleted in</th>
        <th>Size</th>
        <th>Hidden Size</th>
        <th>Files</th>
        <th>Samples</th>
        <th>Owner</th>
        <th>Updated</th>
        <th>Date</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($deletedProjects as $proj)
        <tr>
            <td><a href="{{route('projects.show', [$proj])}}">{{$proj->name}}</a></td>
            <td>{{$proj->deleted_at->addDays($expiresInDays)->diffIndays($now)+1}} days</td>
            <td>{{formatBytes($proj->size)}}</td>
            <td>{{$proj->size}}</td>
            <td>{{number_format($proj->file_count)}}</td>
            <td>{{number_format($proj->entities_count)}}</td>
            <td>{{$proj->owner->name}}</td>
            <td>{{$proj->updated_at->diffForHumans()}}</td>
            <td>{{$proj->updated_at}}</td>
            <td>
                <a href="{{route('dashboard.projects.trash.restore', [$proj])}}" class="action-link">
                    <i class="fas fa-fw fa-trash-restore"></i>
                    restore
                </a>
                <a data-toggle="modal" href="#project-delete-{{$proj->id}}"
                   class="action-link ml-3 text-danger">
                    <i class="fas fa-fw fa-trash-alt"></i> Delete Immediately
                </a>
                @include('app.dashboard.partials._immediate-delete-project-modal', ['project' => $proj])
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@push('scripts')
    <script>
        $(document).ready(() => {
            $('#projects-trash').DataTable({
                pageLength: 100,
                stateSave: true,
                // 0 <th>Project</th>
                // 1 <th>Will be deleted in</th>
                // 2 <th>Size</th>
                // 3 <th>Hidden Size</th>
                // 4 <th>Files</th>
                // 5 <th>Samples</th>
                // 6 <th>Owner</th>
                // 7 <th>Updated</th>
                // 8 <th>Date</th>
                // 9 <th></th>
                columnDefs: [
                    {targets: [3], visible: false}, // Hide Hidden Size
                    {targets: [8], visible: false, searchable: false}, // Hide Date don't make searchable
                    {orderData: [8], targets: [7]}, // Updated [7] uses Date [8] for sorting
                    {orderData: [3], targets: [2]}, // Size [2] uses Hidden Size [3] for sorting
                ]
            });
        });
    </script>
@endpush
