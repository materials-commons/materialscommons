@component('components.card')
    @slot('header')
        Workflows
        <a class="float-right action-link mr-4"
           href="{{route('projects.datasets.workflows.edit.create', [$project, $dataset])}}">
            <i class="fas fa-fw fa-plus mr-2"></i>New Workflow
        </a>
    @endslot

    @slot('body')
        <table id="entities" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Experiments</th>
                <th>Description</th>
                <th>Selected</th>
            </tr>
            </thead>
            <tbody>
            @foreach($workflows as $workflow)
                <tr>
                    <td>
                        <a href="#">{{$workflow->name}}</a>
                    </td>
                    <td>{{$workflowExperiments($workflow)}}</td>
                    <td>{{$workflow->description}}</td>
                    <td>
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$workflow->uuid}}"
                                   {{$workflowInDataset($workflow) ? 'checked' : ''}}
                                   onclick="updateWorkflowSelection({{$workflow}}, this)">
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
        let route = "{{route('api.projects.datasets.workflows', [$dataset])}}";

        $(document).ready(() => {
            $('#entities').DataTable({
                stateSave: true,
            });
        });

        function updateWorkflowSelection(workflow, checkbox) {
            if (checkbox.checked) {
                addWorkflow(workflow);
            } else {
                removeWorkflow(workflow);
            }
        }

        function addWorkflow(workflow) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                workflow_id: workflow.id,
            });
        }

        function removeWorkflow(workflow) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                workflow_id: workflow.id,
            });
        }
    </script>
@endpush
