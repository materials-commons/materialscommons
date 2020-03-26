<table id="entities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Sample</th>
        <th>Summary</th>
        <th>Updated</th>
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
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
