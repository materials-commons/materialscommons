@extends('layouts.app')

@section('pageTitle', "{$project->name} - Attach Workflows")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.edit', $project, $experiment, $workflow))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Attach Workflows To Experiment {{$experiment->name}}
        @endslot

        @slot('body')
            <table id="workflows" class="table table-hover" x-data="experimentWorkflowsAttach">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Selected</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->workflows as $workflow)
                    <tr>
                        <td>
                            {{$workflow->name}}
                        </td>
                        <td>
                            <div class="form-group form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$workflow->uuid}}"
                                       {{$workflowInExperiment($workflow) ? 'checked' : ''}}
                                       @click.prevent="updateWorkflowSelection({{$workflow}}, this)">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br>
            <div class="float-right mr-2">
                <a class="action-link" href="{{route('projects.experiments.show', [$project, $experiment])}}">Done</a>
            </div>

        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#workflows').DataTable({stateSave: true});
        });

        mcutil.onAlpineInit("experimentWorkflowsAttach", () => {
            return {
                projectId: "{{$project->id}}",
                apiToken: "{{$user->api_token}}",
                route: "{{route('api.projects.experiments.workflows.selection', [$experiment])}}",

                updateWorkflowSelection(wf, checkbox) {
                    this.toggleWorkflow(wf);
                },

                toggleWorkflow(wf) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        workflow_id: wf.id,
                    });
                }
            }
        });
    </script>
@endpush
