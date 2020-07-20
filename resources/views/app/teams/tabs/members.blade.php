<h4>Project Members</h4>
<br>
<a href="#" class="action-link float-right">
    <i class="fas fa-fw fa-plus"></i> Add Member
</a>
<table id="team-members" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>User</th>
        <th>Affiliations</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($team->members as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->affiliations}}</td>
            <td>{{$user->description}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#team-members').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush