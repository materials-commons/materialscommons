@include('partials.communities.show-tabs._show-overview')
<table id="files" class="table table-hover">
    <thead>
    <tr>
        <th>File</th>
        <th>Description</th>
        <th>Owner</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->files as $file)
        <tr>
            <td>
                <a href="{{route('communities.files.show', [$community, $file])}}">{{$file->name}}</a>
            </td>
            <td>{{$file->summary}}</td>
            <td>{{$file->owner->name}}</td>
            <td>{{$file->updated_at->diffForHumans()}}</td>
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
