<div>
    <table id="globus-files" class="table table-hover" style="width:100%">
        <thead>
        <th>File</th>
        <th>Status</th>
        </thead>
    </table>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            let dt = $('#globus-files').DataTable({
                pageLength: 100,
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('projects.globus.dt-file-upload-status', [$globusRequest->project_id, $globusRequest])}}",
                columns: [
                    {
                        name: 'name',
                        render: function (data, type, row) {
                            return data;
                        }
                    },
                    {name: 'state'},
                ]
            });
        });
    </script>
@endpush
