@include('app.projects.datasets.ce-tabs._other-tabs-done')
@include('app.projects.datasets.ce-tabs._short-overview')
<h5>
    Workflows will be added or removed automatically as you select them.
</h5>
<br>
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
                <th>Summary</th>
                <th>Selected</th>
                <th></th>
            </tr>
            </thead>
            <tbody x-data="ceTabsWorkflows">
            @foreach($workflows as $workflow)
                <tr>
                    <td>
                        {{$workflow->name}}
                    </td>
                    <td>{{$workflowExperiments($workflow)}}</td>
                    <td>{{$workflow->summary}}</td>
                    <td>
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$workflow->uuid}}"
                                   {{$workflowInDataset($workflow) ? 'checked' : ''}}
                                   onclick="updateWorkflowSelection({{$workflow}}, this)">
                        </div>
                    </td>
                    <td>
                        <a href="{{route('projects.datasets.workflows.edit.workflow', [$project, $dataset, $workflow])}}"
                           class="action-link">
                            <i class="fas fa-fw fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });

        mcutil.onAlpineInit('ceTabsWorkflows', () => {
            return {
                projectId: "{{$project->id}}",
                datasetId: "{{$dataset->id}}",
                apiToken: "{{$user->api_token}}",
                route: "{{route('api.projects.datasets.workflows', [$dataset])}}",
                workflowsCount: {{$dataset->workflows->count()}},

                updateWorkflowSelection(workflow, checkbox) {
                    if (checkbox.checked) {
                        this.workflowsCount++;
                        this.addWorkflow(workflow);
                    } else {
                        this.workflowsCount--;
                        this.removeWorkflow(workflow);
                    }

                    if (this.workflowsCount > 0) {
                        this.setWorkflowsStatusPositive();
                    } else {
                        this.setWorkflowsStatusMissing();
                    }
                },

                addWorkflow(workflow) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        workflow_id: workflow.id,
                    });
                },

                removeWorkflow(workflow) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        workflow_id: workflow.id,
                    });
                },

                setWorkflowsStatusPositive() {
                    // first clear classes, then add the classes we want
                    $('#workflows-step').removeClass('step-success')
                        .addClass('step-success');
                    $('#workflows-circle').removeClass('fa-check fa-circle')
                        .addClass('fa-check');
                },

                setWorkflowsStatusMissing() {
                    // first clear classes, then add the classes we want
                    $('#workflows-step').removeClass('step-success');
                    $('#workflows-circle').removeClass('fa-check fa-circle')
                        .addClass('fa-circle');
                }
            }
        });
    </script>
@endpush
