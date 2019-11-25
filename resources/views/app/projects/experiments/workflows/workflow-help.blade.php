<br>
<a onclick="toggleHelp()" href="#" id="helplink">Show Workflow Help</a>
<div class="form-group" id="help" hidden>
    <p>
        Workflows are specified using a simple language. Each step in your workflow is separated
        by a '->'. Conditional steps have names that contain a '?' in them.
    </p>
    <p>
        When specifying a step you can optionally give a direction in parathensis. When specifying
        a conditional step, you can specify the true or false name. Below is an example workflow.
    </p>
    <pre>
                        Heat Treat Sample?(yes, right)->Heat Treat at 400c/3h->SEM(right)->Analyze
                        Heat Treat Sample?(no)->SEM->Analyze
                    </pre>

</div>
<div id="exampleworkflow"></div>
@push('scripts')
    <script>
        $(document).ready(() => {
            let code = "Heat Treat Sample?(yes, right)->Heat Treat at 400c/3h(right)->SEM(right)->Analyze\nHeat Treat Sample?(no)->SEM->Analyze";
            let examplefl = simplefl.parseSimpleFlowchart(code);
            mcfl.drawFlowchart('exampleworkflow', examplefl);
            $('#exampleworkflow').attr('hidden', true);
        });

        function toggleHelp() {
            let current = $('#help').attr('hidden');
            if (current) {
                $('#help').attr('hidden', false);
                $('#exampleworkflow').attr('hidden', false);
                $('#helplink').html('Hide Workflow Help');
            } else {
                $('#help').attr('hidden', true);
                $('#exampleworkflow').attr('hidden', true);
                $('#helplink').html('Show Workflow Help');
            }
        }

    </script>
@endpush