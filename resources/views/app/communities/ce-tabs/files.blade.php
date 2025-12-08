<h3>Community Files</h3>
<a class="float-end action-link me-2" href="{{route('communities.files.upload', [$community])}}">
    <i class="fas fa-fw fa-plus me-2"></i>Add Files
</a>
<br/>
<br/>

<table id="files" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Size</th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->files as $file)
        <tr>
            <td>
                <a href="{{route('communities.files.show', [$community, $file])}}">
                    <i class="fa-fw fas me-2 fa-file"></i>{{$file->name}}
                </a>
            </td>
            <td>{{$file->mime_type}}</td>
            <td>{{$file->toHumanBytes()}}</td>
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
            });
        });
    </script>
@endpush
