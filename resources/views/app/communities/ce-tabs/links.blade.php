<h3>Recommend Practices Links</h3>
<a class="float-end action-link me-2" href="{{route('communities.links.create', [$community])}}">
    <i class="fas fa-fw fa-plus me-2"></i>Add Link
</a>
<br/>
<br/>

<table id="links" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->links as $link)
        <tr>
            <td>
                <a href="{{$link->url}}" target="_blank">
                    <i class="fa-fw fas me-2 fa-external-link-alt"></i>{{$link->name}}
                </a>
            </td>
            <td>{{$link->summary}}</td>
            <td>
                <a class="action-link" href="{{route('communities.links.edit-link', [$community, $link])}}">
                    <i class="fas fa-fw fa-edit"></i>
                </a>
                <a class="action-link" href="{{route('communities.links.delete', [$community, $link])}}">
                    <i class="fas fa-fw fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#links').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
