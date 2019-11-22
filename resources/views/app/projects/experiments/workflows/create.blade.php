@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop


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
        @endslot
    @endcomponent
    @push('scripts')
        <script>
            let workflowgraph;

            function drawWorkflow() {
                if (workflowgraph) {
                    workflowgraph.clean();
                }
                let code = document.getElementById('workflow').value;
                let fl = simplefl.parseSimpleFlowchart(code);
                workflowgraph = mcfl.drawFlowchart('workflowcanvas', fl);
            }
        </script>
    @endpush
@stop