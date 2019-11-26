@extends('layouts.app')

@section('pageTitle', 'Workflows')

@section('nav')
    @include('layouts.navs.project')
@endsection

@section('content')
    @component('components.card')
        @slot('header')
            Modify Dataset {{$dataset->name}} Workflows
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
            <br><br>
            <a class="float-right btn btn-success"
               href="{{route('projects.datasets.edit', [$project, $dataset])}}">Done</a>
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
@stop