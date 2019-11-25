@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.workflows.create', $project, $experiment))

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow for Experiment {{$experiment->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.experiments.workflows.store', [$project, $experiment])}}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" placeholder="Workflow Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              placeholder="Description..."></textarea>
                </div>

                <div class="form-group">
                    <label for="workflow">Workflow</label>
                    <textarea class="form-control" id="workflowcode" name="workflow"
                              placeholder="Workflow..."></textarea>
                </div>

                <div class="float-right">
                    <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
                    <button class="btn btn-success">Save</button>
                    <button type="button" onclick="cancel()" class="btn btn-warning">Cancel</button>
                </div>
            </form>
            <div id="workflowcanvas"></div>

            @include('app.projects.experiments.workflows.workflow-help')

        @endslot
    @endcomponent
    @push('scripts')
        <script>
            let workflowgraph;

            // $(document).ready(() => {
            //     let code ="Heat Treat Sample?(yes, right)->Heat Treat at 400c/3h(right)->SEM(right)->Analyze\nHeat Treat Sample?(no)->SEM->Analyze";
            //     let examplefl = simplefl.parseSimpleFlowchart(code);
            //     mcfl.drawFlowchart('exampleworkflow', examplefl);
            //     $('#exampleworkflow').attr('hidden', true);
            // });

            function drawWorkflow() {
                if (workflowgraph) {
                    workflowgraph.clean();
                }
                let code = document.getElementById('workflowcode').value;
                let fl = simplefl.parseSimpleFlowchart(code);
                workflowgraph = mcfl.drawFlowchart('workflowcanvas', fl);
            }

            // function toggleHelp() {
            //     let current = $('#help').attr('hidden');
            //     if (current) {
            //         $('#help').attr('hidden', false);
            //         $('#exampleworkflow').attr('hidden', false);
            //         $('#helplink').html('Hide Workflow Help');
            //     } else {
            //         $('#help').attr('hidden', true);
            //         $('#exampleworkflow').attr('hidden', true);
            //         $('#helplink').html('Show Workflow Help');
            //     }
            // }

            function cancel() {
                window.location.href = "{{route('projects.experiments.show', [$project, $experiment])}}";
            }
        </script>
    @endpush
@stop