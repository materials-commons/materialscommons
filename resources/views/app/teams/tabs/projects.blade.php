<h4>Projects In Team</h4>
<br>
<a href="#" class="action-link float-right">
    <i class="fas fa-fw fa-plus"></i> Add Project
</a>
<table id="team-projects" class="bootstrap-table bootstrap-table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Project</th>
        <th>Description</th>
        <th>Last Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($team->projects as $project)
        <tr>
            <td>
                <a href="{{route('projects.show', [$project])}}">{{$project->name}}</a>
            </td>
            <td>{{$project->summary}}</td>
            <td>{{$project->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#team-projects').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush