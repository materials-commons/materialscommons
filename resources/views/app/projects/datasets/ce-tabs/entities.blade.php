@include('app.projects.datasets.ce-tabs._other-tabs-done')
@include('app.projects.datasets.ce-tabs._short-overview')
<h5>
    Samples will be added or removed automatically as you select them.
</h5>
<br>
<div x-data="datasetsCETabsEntities">
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
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });

        mcutil.onAlpineInit("datasetsCETabsEntities", () => {
            return {
                projectId: "{{$project->id}}",
                datasetId: "{{$dataset->id}}",
                apiToken: "{{$user->api_token}}",
                route: "{{route('api.projects.datasets.entities', [$dataset])}}",
                samplesCount: {{$entitiesCountInDataset}},

                checkAllEntities() {
                    let self = this;
                    $('.entity-checkbox').each(function () {
                        if (this.checked) {
                            return;
                        }
                        self.samplesCount++;
                        this.checked = true;
                        this.onclick();
                    });
                    this.setSamplesStatusPositive();
                },

                uncheckAllEntities() {
                    $('.entity-checkbox').each(function () {
                        if (!this.checked) {
                            return;
                        }
                        this.checked = false;
                        this.onclick();
                    });
                    this.samplesCount = 0;
                    this.setSamplesStatusMissing();
                },

                updateEntitySelection(entity, checkbox) {
                    if (checkbox.checked) {
                        this.samplesCount++;
                        this.addEntity(entity);
                    } else {
                        this.samplesCount--;
                        this.removeEntity(entity);
                    }

                    if (this.samplesCount > 0) {
                        this.setSamplesStatusPositive();
                    } else {
                        this.setSamplesStatusMissing();
                    }
                },

                addEntity(entity) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        entity_id: entity.id,
                    });
                },

                removeEntity(entity) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        entity_id: entity.id,
                    });
                },

                setSamplesStatusPositive() {
                    // first clear classes, then add the classes we want
                    $('#samples-step').removeClass('step-success')
                        .addClass('step-success');
                    $('#samples-circle').removeClass('fa-check fa-circle')
                        .addClass('fa-check');
                },

                setSamplesStatusMissing() {
                    // first clear classes, then add the classes we want
                    $('#samples-step').removeClass('step-success');
                    $('#samples-circle').removeClass('fa-check fa-circle')
                        .addClass('fa-circle');
                }
            }
        });
    </script>
@endpush
