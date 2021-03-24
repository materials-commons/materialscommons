<div>
    <table id="authors" class="table table-hover" style="width: 100%">
        <thead>
        <tr>
            <td>Seq.</td>
            <th>Name</th>
            <th>Affiliations</th>
            <th>Email</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->team->members->merge($project->team->admins) as $author)
            <tr>
                <td>{{$loop->index}}</td>
                <td>{{$author->name}}</td>
                <td>{{$author->affiliations}}</td>
                <td>{{$author->email}}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#authors").DataTable({
                rowReorder: {
                    selector: 'tr'
                },
            });
        });
    </script>
@endpush
