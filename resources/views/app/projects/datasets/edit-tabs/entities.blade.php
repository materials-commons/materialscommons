@component('components.card')
    @slot('header')
        Samples
    @endslot

    @slot('body')
        <table id="entities" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Experiments</th>
                <th>Selected</th>
            </tr>
            </thead>
            <tbody>
            @foreach($project->entities as $entity)
                <tr>
                    <td>
                        <a href="#">{{$entity->name}}</a>
                    </td>
                    <td>{{$entityExperiments($entity)}}</td>
                    <td>
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$entity->uuid}}"
                                   {{$entityInDataset($entity) ? 'checked' : ''}}
                                   onclick="updateEntitySelection({{$entity}}, this)">
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        let projectId = "{{$project->id}}";
        let datasetId = "{{$dataset->id}}";
        let apiToken = "{{$user->api_token}}";
        let route = "{{route('api.projects.datasets.entities', [$dataset])}}";

        $(document).ready(() => {
            $('#entities').DataTable({
                stateSave: true,
            });
        });

        function updateEntitySelection(entity, checkbox) {
            if (checkbox.checked) {
                addEntity(entity);
            } else {
                removeEntity(entity);
            }
        }

        function addEntity(entity) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                entity_id: entity.id,
            });
        }

        function removeEntity(entity) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                entity_id: entity.id,
            });
        }
    </script>
@endpush
