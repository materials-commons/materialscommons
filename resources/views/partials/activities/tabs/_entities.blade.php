<table id="entities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Sample</th>
        <th>Description</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($activity->entities as $entity)
        <tr>
            <td>
                <a href="{{$showEntityRoute($entity)}}">{{$entity->name}}</a>
            </td>
            <td>{{$entity->description}}</td>
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