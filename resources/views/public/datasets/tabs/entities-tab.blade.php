<table id="entities" class="table table-hover" style="width:100%">
    <thead>
    <th>Sample</th>
    <th>Description</th>
    <th>Updated</th>
    </thead>
    <tbody>
    @foreach($dataset->entities as $entity)
        <tr>
            <td>
                <a href="{{route('public.datasets.entities.show', [$dataset, $entity])}}">{{$entity->name}}</a>
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
