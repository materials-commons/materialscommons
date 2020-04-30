<p>
    These are the recommended practices and templates for the community.
</p>
<br>
<h3>Files</h3>
<br>
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
                <a href="">{{$file->name}}</a>
            </td>
            <td>{{$dataset->summary}}</td>
            <td>{{$dataset->owner->name}}</td>
            <td>{{$dataset->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>
<hr>
<br>

<h3>Links</h3>
<br>
<table id="links" class="table table-hover">
    <thead>
    <tr>
        <th>Link</th>
        <th>Owner</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->links as $link)
        <tr>
            <td>
                <a href="{{$link->url}}" target="_blank">{{$link->summary}}</a>
            </td>
            <td>{{$link->owner->name}}</td>
            <td>{{$link->updated_at->diffForHumans()}}</td>
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
            $('#links').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush