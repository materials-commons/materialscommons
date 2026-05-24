<h4>Project Administators</h4>
<br>
<a href="#" class="action-link float-end">
    <i class="fas fa-fw fa-plus"></i> Add Admin
</a>
<table id="team-admins" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>User</th>
        <th>Affiliations</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($team->admins as $user)
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
            $('#team-admins').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush