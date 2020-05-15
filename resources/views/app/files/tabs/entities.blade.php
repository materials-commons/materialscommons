<table id="entities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Sample</th>
        <th>Summary</th>
        <th>Updated</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($file->entities as $entity)
        <tr>
            <td>
                @isset($project)
                    <a href="{{route('projects.entities.show', [$project, $entity])}}">{{$entity->name}}</a>
                @endisset
            </td>
            <td>{{$entity->summary}}</td>
            <td>{{$entity->updated_at->diffForHumans()}}</td>
            <td>{{$entity->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities').DataTable({
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
