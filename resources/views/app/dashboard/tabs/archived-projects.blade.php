@if($archivedCount == 0)
    <h4>There are no archived projects</h4>
@else
    <table id="archived-projects" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Project</th>
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
        @foreach($archivedProjects as $proj)
            <tr>
                <td>
                    <a href="{{route('projects.show', [$proj->id])}}" class="">{{$proj->name}}</a>
                </td>
                <td>{{formatBytes($proj->size)}}</td>
                <td>{{$proj->size}}</td>
                <td>{{number_format($proj->file_count)}}</td>
                <td>{{number_format($proj->entities_count)}}</td>
                <td>{{$proj->owner->name}}</td>
                <td>{{$proj->updated_at->diffForHumans()}}</td>
                <td>{{$proj->updated_at}}</td>
                <td>
                    <div class="float-right">
                        @if(auth()->id() == $proj->owner_id)
                            <a data-toggle="modal" href="#project-delete-{{$proj->id}}"
                               class="action-link">
                                <i class="fas fa-fw fa-trash-alt"></i>
                            </a>
                            <a href="{{route('dashboard.projects.unarchive',[$proj])}}"
                               data-toggle="tooltip"
                               title="Marks project as unarchived. Project will show up in the Projects tab."
                               class="action-link">
                                <i class="fas fa-fw fa-arrow-alt-circle-left"></i>
                            </a>
                        @endif
                    </div>
                    @if(auth()->id() == $proj->owner_id)
                        @component('app.projects.delete-project', ['project' => $proj])
                        @endcomponent
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @push('scripts')
        <script>
            $(document).ready(() => {
                // 0 <th>Project</th>
                // 1 <th>Size</th>
                // 2 <th>Hidden Size</th>
                // 3 <th>Files</th>
                // 4 <th>Samples</th>
                // 5 <th>Owner</th>
                // 6 <th>Updated</th>
                // 7 <th>Date</th>
                // 8 <th></th>
                $('#archived-projects').DataTable({
                    stateSave: true,
                    pageLength: 100,
                    columnDefs: [
                        {targets: [2], visible: false},
                        {targets: [7], visible: false, searchable: false},
                        {orderData: [2], targets: [1]},
                        {orderData: [7], targets: [6]}
                    ],
                });
            });
        </script>
    @endpush
@endif