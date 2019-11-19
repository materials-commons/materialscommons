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
            <div id="controls">
                <div class="row">
                    <input id="step" placeholder="Add Step...">
                    <input id="branch" class="ml-2" placeholder="Add Branch...">
                </div>
                <div class="row mt-2">
                    <textarea id="steps" style="width:100%" rows="2" readonly>Receive Samples, Finished</textarea>
                </div>
                <div class="row mt-2">
                    <button class="btn btn-success">
                        <i class="fas fa-plus mr-1"></i>Step
                    </button>
                    <button class="btn btn-success ml-2">
                        <i class="fas fa-plus mr-1"></i>Branch
                    </button>
                </div>
                <div class="row mt-2">
                    <select class="selectpicker col-lg-4" data-live-search>
                        <option data-token="Receive Samples" value="Receive Samples" selected>Receive Samples</option>
                        <option data-token="Finished" value="Finished">Finished</option>
                    </select>
                    <span class="ml-2 mr-2"><i class="fas fa-3x fa-arrow-right"></i></span>
                    <select class="selectpicker col-lg-4" data-live-search>
                        <option data-token="Receive Samples" value="Receive Samples">Receive Samples</option>
                        <option data-token="Finished" value="Finished" selected>Finished</option>
                    </select>
                    <a><i class="fas fa-trash fa-2x"></i></a>
                </div>
            </div>
            <br/>
            <br/>
            <div id="workflow">
            </div>
            <br/>
            <br/>
            <div id="row">
                <textarea id="code" rows="10" style="width:100%" readonly></textarea>
            </div>
        @endslot
    @endcomponent
    @push('scripts')
        <script>
            let fl = null;
            let workflow = `st=>start: Receive Samples\ne=>end: Finished\nst->e`;
            $(document).ready(() => {

                $('#code').val(workflow);
                drawWorkflow();
                $('#step').on('keyup', (e) => {
                    if (e.keyCode === 13) {
                        let v = $('#steps').val();
                        v = `${v}, ${e.target.value}`;
                        $('#steps').val(v);
                        let firstArray = workflow.indexOf('->');
                        let newLine = workflow.lastIndexOf("\n", firstArray);
                        let value = e.target.value;
                        workflow = workflow.substring(0, newLine) + "\n" + `${value}=>operation: ${value}` + workflow.substring(newLine);
                        $('#code').val(workflow);
                        $('#step').val('');
                    }
                });
            });

            function drawWorkflow() {
                if (fl !== null) {
                    flowchart.clean();
                }
                let code = document.getElementById('code').value;
                fl = mcfl.drawFlowchart('workflow', code);
            }
        </script>
    @endpush
@stop