<br>
<a onclick="toggleHelp()" href="#" id="helplink">Show Workflow Help</a>

<div id="help" hidden>
    <br>
    <p>
        Workflows are specified using a simple language. Each step in your workflow is separated
        by a '->'. Conditional steps have names that contain a '?' in them.
    </p>
    <p>You <u><b>must not</b></u> have spaces between nodes. The following is invalid (because there is a space before
        and after the '->'.
    </p>
    <pre>
        Heat Treatment -> Analyze
    </pre>
    <p>
        When specifying a step you can optionally give a direction in parathensis. When specifying
        a conditional step, you can specify the text on the connecting arrow. Below is the code and result for
        an example workflow.
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