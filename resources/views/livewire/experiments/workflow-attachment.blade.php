<div>
    <h3 class="text-center">Attach Workflows To Study {{ $experiment->name }}</h3>

    <br/>

    <table id="workflows" class="table table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Selected</th>
        </tr>
        </thead>
        <tbody>
        @foreach($workflows as $workflow)
            <tr wire:key="experiment-workflow-attachment-{{ $workflow->id }}">
                <td>
                    {{ $workflow->name }}
                </td>
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               id="{{ $workflow->uuid }}"
                               wire:click="toggleWorkflow({{ $workflow->id }})"
                            @checked($this->workflowInExperiment($workflow))>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>

    <div class="float-end me-2">
        <a class="action-link" href="{{ route('projects.experiments.show', [$project, $experiment]) }}">Done</a>
    </div>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#workflows').DataTable({stateSave: true});
            });
        </script>
    @endpush
</div>
