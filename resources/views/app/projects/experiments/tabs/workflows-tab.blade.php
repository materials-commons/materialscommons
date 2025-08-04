<x-table-container>
    <br>
    <div class="ml-2">
        <div class="float-right mr-2">
            <a href="{{route('projects.experiments.workflows.create', [$project, $experiment])}}" class="action-link">
                <i class="fas fa-fw fa-plus"></i> New Workflow
            </a>
            <a href="{{route('projects.experiments.workflows.attach', [$project, $experiment])}}"
               class="action-link ml-3">
                <i class="fas fa-fw fa-link"></i> Attach Workflow
            </a>
        </div>
        @include('partials.workflows.index', [
            'workflows' => $workflows,
            'editExperimentWorkflowRoute' => 'projects.experiments.workflows.edit',
        ])
    </div>
</x-table-container>
