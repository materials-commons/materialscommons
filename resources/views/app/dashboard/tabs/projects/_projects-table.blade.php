<div>
    <h5 class="card-title">All Projects</h5>
    <hr class="mb-6x">
    <br/>
    <table id="projects" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Project</th>
            <th>Files</th>
            <th>Published Datasets</th>
            <th>Owner</th>
            <th>Updated</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $proj)
            <tr>
                <td>
                    <a href="{{route('projects.show', [$proj->id])}}" class="">{{$proj->name}}</a>
                </td>
                <td>{{number_format($proj->file_count)}}</td>
                <td>{{number_format($proj->published_datasets_count)}}</td>
                <td>{{$proj->owner->name}}</td>
                <td>{{$proj->updated_at->format('M j, Y')}}</td>
                <td>
                    <div class="float-right">
                        @if(auth()->id() == $proj->owner_id)
                            <a data-toggle="modal" href="#project-delete-{{$proj->id}}"
                               class="action-link">
                                <i class="fas fa-fw fa-trash-alt"></i>
                            </a>
                            <a href="{{route('dashboard.projects.archive',[$proj])}}"
                               data-toggle="tooltip"
                               title="Marks project as archived. Project will show up in the Archived Projects tab."
                               class="action-link">
                                <i class="fas fa-fw fa-archive"></i>
                            </a>
                            @component('app.projects.delete-project', ['project' => $proj])
                            @endcomponent
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        // document.addEventListener('livewire:navigating', () => {
        //     $('#projects').DataTable().destroy();
        // }, {once: true});

        let projectsCount = "{{sizeof($projects)}}";
        $(document).ready(() => {
            if (projectsCount === "0") {
                $('#welcome-dialog').modal();
            }
            $('#projects').DataTable({
                stateSave: true,
                pageLength: 100,
            });
        });
    </script>
@endpush
