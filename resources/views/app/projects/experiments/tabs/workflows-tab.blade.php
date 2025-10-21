<x-table-container>
    <br>
    <div class="ms-2">
        <div class="float-end me-2">
            <a href="{{route('projects.experiments.workflows.create', [$project, $experiment])}}" class="action-link">
                <i class="fas fa-fw fa-plus me-1"></i> New Workflow
            </a>
            <a href="{{route('projects.experiments.workflows.attach', [$project, $experiment])}}"
               class="action-link ms-3">
                <i class="fas fa-fw fa-link me-1"></i> Attach Workflow
            </a>
        </div>
        @include('partials.workflows.index', [
            'workflows' => $workflows,
            'editExperimentWorkflowRoute' => 'projects.experiments.workflows.edit',
        ])
    </div>
</x-table-container>
