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
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "",
                columns: []
            });
        });
    </script>
@endpush