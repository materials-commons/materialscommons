@include('public.datasets.tabs._short-overview')
<table id="files" class="table table-hover" style="width: 100%">
    <thead>
    <th>Name</th>
    <th>ID</th>
    <th>Type</th>
    <th>Size</th>
    <th>Hidden Size</th>
    </thead>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#files').DataTable({
                pageLength: 100,
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('public.dt_get_published_dataset_files', [$dataset])}}",
                columns: [
                    {
                        name: 'name',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                let rowType = row["2"];
                                let objectId = row["1"];
                                let r = route('public.datasets.files.show', [{{$dataset->id}}, objectId]);
                                let icon = `<i class="fa-fw fas mr-2 fa-file"></i>`;
                                let ndata = `<a href="${r}">${icon} ${data}</a>`;
                                return ndata;
                            }

                            return data;
                        }
                    },
                    {name: 'id'},
                    {name: 'mime_type'},
                    {
                        name: 'size',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return formatters.humanFileSize(data);
                            }
                            return data;
                        }
                    },
                    {name: 'size'},
                ],
                columnDefs: [
                    {targets: [1], visible: false},
                    {orderData: [4], targets: [3]},
                    {targets: [4], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
