<table id="files" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>File</th>
        <th>Description</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entity->files as $file)
        <tr>
            <td>
                <a href="#">{{$file->name}}</a>
            </td>
            <td>{{$file->description}}</td>
            <td>{{$file->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#files').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
