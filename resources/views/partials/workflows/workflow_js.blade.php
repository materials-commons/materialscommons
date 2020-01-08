@push('scripts')
    <script>
        let workflowgraph;

        function drawWorkflow() {
            if (workflowgraph) {
                workflowgraph.clean();
            }
            let code = document.getElementById('workflowcode').value;
            let fl = simplefl.parseSimpleFlowchart(code);
            workflowgraph = mcfl.drawFlowchart('workflowcanvas', fl);
        }

        @isset($cancelRoute)
        function cancel() {
            window.location.href = "{{$cancelRoute}}";
        }
        @endisset
    </script>
@endpush