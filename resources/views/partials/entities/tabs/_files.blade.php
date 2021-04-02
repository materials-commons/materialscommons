<table id="files" class="bootstrap-table bootstrap-table-hover" style="width:100%">
    <thead>
    <tr>
        <th>File</th>
        <th>Description</th>
        <th>Updated</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entity->files as $file)
        <tr>
            <td>
                <a href="{{$showFileRoute($file)}}">{{$file->name}}</a>
            </td>
            <td>{{$file->description}}</td>
            <td>{{$file->updated_at->diffForHumans()}}</td>
            <td>{{$file->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#files').DataTable({
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush