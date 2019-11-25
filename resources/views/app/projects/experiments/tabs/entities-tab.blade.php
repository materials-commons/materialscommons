{{--<a class="action-link float-right"--}}
{{--   href="{{route('projects.create')}}">--}}
{{--    <i class="fas fa-plus mr-2"></i>Create Sample--}}
{{--</a>--}}

<table id="entities" class="table table-hover" style="width:100%">
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
            $('#entities').DataTable({
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('dt_get_experiment_entities', [$project->id, $experiment->id])}}",
                columns: [
                    {
                        name: 'name',
                        render: (data, type, row) => {
                            if (type !== 'display') {
                                return data;
                            }
                            let r = route('projects.experiments.entities.show', {
                                project: "{{$project->id}}",
                                experiment: "{{$experiment->id}}",
                                entity: row["1"]
                            }).url();
                            return `<a href="${r}">${data}</a>`;
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
