<a class="btn btn-success" href="{{route('projects.create')}}">
    <i class="fas fa-plus mr-2"></i>Create Project
</a>
<br>
<br>
<h4>Active Projects</h4>
<div class="row">
    @foreach($projects as $proj)
        @if(!auth()->user()->isActiveProject($proj))
            @continue
        @endif

        @php
            $recentlyAccessedOn = auth()->user()->projectRecentlyAccessedOn($proj);
        @endphp

        @include('app.dashboard.tabs._project-card')
    @endforeach
</div>
<br/>
<h4>Recently Accessed Projects</h4>
<div class="row">
    @foreach($projects as $proj)
        @if(auth()->user()->isActiveProject($proj))
            @continue
        @endif

        @php
            $recentlyAccessedOn = auth()->user()->projectRecentlyAccessedOn($proj);
        @endphp

        @if(is_null($recentlyAccessedOn))
            @continue
        @endif

        @include('app.dashboard.tabs._project-card')
    @endforeach
</div>
<br>
<br>
<table id="projects" class="table table-hover" style="width:100%">
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
    @foreach($projects as $proj)
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
        let projectsCount = "{{sizeof($projects)}}";
        $(document).ready(() => {
            if (projectsCount === "0") {
                $('#welcome-dialog').modal();
            }
            // 0 <th>Project</th>
            // 1 <th>Size</th>
            // 2 <th>Hidden Size</th>
            // 3 <th>Files</th>
            // 4 <th>Samples</th>
            // 5 <th>Owner</th>
            // 6 <th>Updated</th>
            // 7 <th>Date</th>
            // 8 <th></th>
            $('#projects').DataTable({
                // stateSave: true,
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