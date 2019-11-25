@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.edit', $project, $experiment, $workflow))

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow for Experiment {{$experiment->name}}
        @endslot

        @slot('body')
            <div id="codearea" class="col-lg-10">
                <form method="post"
                      action="{{route('projects.experiments.workflows.update', [$project, $experiment, $workflow])}}"
                      id="edit-workflow">

                    @csrf
                    @method('patch')
                    <input hidden name="project_id" value="{{$project->id}}">
                    <div class="form-group">
                        <label for="description">Workflow</label>
                        <textarea id="code" style="width:100%" rows="4"
                                  name="workflow">{{$workflow->workflow}}</textarea>
                    </div>
                    <div class="float-right">
                        <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
                        <button class="btn btn-success">Save</button>
                        <button type="button" onclick="resetAndClose()" class="btn btn-warning">Cancel</button>
                    </div>
                </form>
            </div>
            <br>
            <div id="workflow"></div>
            @include('app.projects.experiments.workflows.workflow-help')
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            let chart, savedCode;
            $(document).ready(() => {
                drawWorkflow();
                savedCode = document.getElementById('code').value;
            });

            function drawWorkflow() {
                if (chart) {
                    chart.clean();
                }
                let code = document.getElementById('code').value;
                let fl = simplefl.parseSimpleFlowchart(code);
                chart = mcfl.drawFlowchart('workflow', fl);
            }

            function resetAndClose() {
                window.location.href = "{{route('projects.experiments.show', [$project, $experiment])}}";
            }
        </script>
    @endpush
@stop