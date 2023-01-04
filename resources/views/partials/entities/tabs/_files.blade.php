<table id="files" class="table table-hover" style="width:100%">
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
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
