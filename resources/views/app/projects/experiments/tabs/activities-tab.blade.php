<table id="activities" class="table" width="100%">
    <thead>
    <th>Name</th>
    <th>ID</th>
    <th>Description</th>
    <th>Updated</th>
    </thead>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            let projectId = "{{$project->id}}",
                experimentId = "{{$experiment->id}}";
            $('#activities').DataTable({
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('dt_get_experiment_activities', [$project->id, $experiment->id])}}",
                columns: [
                    {
                        name: 'name',
                        render: (data, type, row) => {
                            if (type !== 'display') {
                                return data;
                            }

                            return `<a href="#">${data}</a>`;
                        }
                    },
                    {name: 'id'},
                    {name: 'description'},
                    {
                        name: 'updated_at',
                        render: (data, type, row) => {
                            if (type !== 'display') {
                                return data;
                            }
                            return moment(data + "+0000", "YYYY-MM-DD HH:mm:ss Z").fromNow();
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: [1],
                        visible: false,
                    }
                ]
            });
        });
    </script>
@endpush