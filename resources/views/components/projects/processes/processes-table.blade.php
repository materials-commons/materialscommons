<div>
    <bold>Need to show experiment samples and processes(?) are in</bold>
    <br/>
    <table id="activities" class="table table-hover" width="100%">
        <thead>
        <th>Process</th>
        <th>Samples</th>
        </thead>
        <tbody>
        @foreach($activityEntities as $name => $entities)
            @if($name != "")
                <tr>
                    <td>
                        <a href="{{route('projects.activities.show-by-name', [$project, $name])}}">{{$name}}</a>
                    </td>
                    <td>
                        @foreach($entities as $entity)
                            <a href="{{route('projects.entities.show', [$project, $entity->id])}}">
                                {{$entity->name}}
                            </a>
                            {{!$loop->last ? ", " : ""}}
                        @endforeach
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>

        @push('scripts')
            <script>
                $(document).ready(() => {
                    $('#activities').DataTable({
                        pageLength: 100,
                        stateSave: true,
                    });
                });
            </script>
        @endpush
    </table>
</div>