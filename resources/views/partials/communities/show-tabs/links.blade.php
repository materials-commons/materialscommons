@include('partials.communities.show-tabs._show-overview')
<table id="links" class="bootstrap-table bootstrap-table-hover">
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
            $('#links').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush