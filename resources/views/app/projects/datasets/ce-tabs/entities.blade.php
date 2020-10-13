@include('app.projects.datasets.ce-tabs._short-overview')
<h5>
    Samples will be added or removed automatically as you select them.
</h5>
<br>
<div class="row">
    <a href="#" class="ml-4 mb-2" onclick="checkAllEntities()">Select All Samples</a>
    <a href="#" class="ml-4 mb-2" onclick="uncheckAllEntities()">Unselect All Samples</a>
</div>
<br>
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
                    <input type="checkbox" class="form-check-input entity-checkbox" id="{{$entity->uuid}}"
                           {{$entityInDataset($entity) ? 'checked' : ''}}
                           onclick="updateEntitySelection({{$entity}}, this)">
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

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

        function checkAllEntities() {
            $('.entity-checkbox').each(function () {
                if (this.checked) {
                    return;
                }
                this.checked = true;
                this.onclick();
            });
        }

        function uncheckAllEntities() {
            $('.entity-checkbox').each(function () {
                if (!this.checked) {
                    return;
                }
                this.checked = false;
                this.onclick();
            });
        }

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
