<div>
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <a class="btn btn-success"
           href="{{ route('projects.datasets.show', [$project, $dataset]) }}">
            <i class="fas fa-check me-1"></i> Save &amp; Exit
        </a>
    </div>

    @include('app.projects.datasets.ce-tabs._short-overview')

    <h5>
        Workflows will be added or removed automatically as you select them.
    </h5>

    <br>

    <div class="mb-4">
        Workflows
        <a class="float-end action-link me-4" href="{{ route('projects.datasets.workflows.edit.create', [$project, $dataset]) }}">
            <i class="fas fa-fw fa-plus me-2"></i>New Workflow
        </a>
    </div>

    <table id="entities" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Studies</th>
            <th>Summary</th>
            <th>Selected</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($workflows as $workflow)
            <tr wire:key="dataset-workflow-selection-{{ $workflow->id }}">
                <td>
                    {{ $workflow->name }}
                </td>
                <td>{{ $this->workflowExperiments($workflow) }}</td>
                <td>{{ $workflow->summary }}</td>
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               id="{{ $workflow->uuid }}"
                               wire:click="toggleWorkflow({{ $workflow->id }})"
                            @checked($this->workflowInDataset($workflow))>
                    </div>
                </td>
                <td>
                    <a href="{{ route('projects.datasets.workflows.edit.workflow', [$project, $dataset, $workflow]) }}"
                       class="action-link">
                        <i class="fas fa-fw fa-edit"></i>
                    </a>
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
