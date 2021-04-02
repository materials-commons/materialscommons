<table id="communities" class="bootstrap-table bootstrap-table-hover">
    <thead>
    <tr>
        <th>Community</th>
        <th>Organizer</th>
        <th>Summary</th>
        <th>Datasets</th>
    </tr>
    </thead>
    <tbody>
    @foreach($communities as $community)
        <tr>
            <td>
                <a href="{{route('public.communities.show', $community)}}">{{$community->name}}</a>
            </td>
            <td>{{$community->owner->name}}</td>
            <td>{{$community->summary}}</td>
            <td>{{$community->datasets_count}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#communities').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
