<div>
    <h3>Recently Uploaded Files</h3>
    <br>
    <table id="recently-uploaded" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>File</th>
            <th>Upload At</th>
            <th>By</th>
            <th>Size</th>
        </tr>
        </thead>
    </table>
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            let projectId = "{{$project->id}}";
            $('#recently-uploaded').DataTable({
                pageLength: 100,
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('projects.dt.recently-uploaded', [$project])}}",
                columns: [
                    {
                        name: 'name',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let path = row[4] == '/' ? "" : row[4];
                                let r = route('projects.files.show', [projectId, row[5]]);
                                return `<a href="${r}">${path}/${data}</a>`;
                            }

                            return data;
                        }
                    },
                    {
                        name: 'created_at',
                        render: function(data) {
                            if (!data) {
                                return "";
                            }
                            let space = data.indexOf(" ");
                            return data.slice(0, space);
                        }
                    },
                    {
                        name: 'owner.name'
                    },
                    {
                        name: 'size'
                    },
                    {
                        name: 'directory.path',
                        visible: false,
                    },
                    {
                        name: 'id',
                        visible: false,
                    }
                ]
            });
        })
    </script>
@endpush
