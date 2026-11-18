<div>
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <a class="btn btn-success"
           href="{{ route('projects.datasets.show', [$project, $dataset]) }}">
            <i class="fas fa-check me-1"></i> Save &amp; Exit
        </a>
    </div>

    @include('app.projects.datasets.ce-tabs._short-overview')

    <h5>
        Samples will be added or removed automatically as you select them.
    </h5>

    <br>

    <div class="float-end">
        <a href="#" class="ms-4 mb-2" wire:click.prevent="selectAllEntities">Select All Samples</a>
        <a href="#" class="ms-4 mb-2" wire:click.prevent="unselectAllEntities">Unselect All Samples</a>
    </div>

    <br>

    <table id="entities" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Studies</th>
            <th>Selected</th>
        </tr>
        </thead>
        <tbody>
        @foreach($entities as $entity)
            <tr wire:key="dataset-entity-selection-{{ $entity->id }}">
                <td>
                    <a href="#">{{ $entity->name }}</a>
                </td>
                <td>{{ $entityExperiments($entity) }}</td>
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input entity-checkbox"
                               id="{{ $entity->uuid }}"
                               wire:click="toggleEntity({{ $entity->id }})"
                            @checked($entityInDataset($entity))>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#entities').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
</div>
