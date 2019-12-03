<div class="ml-2">
    <table id="workflows" class="table table-hover">
        <thead>
        <tr>
            <th>Workflow</th>
            <th>Description</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dataset->workflows as $workflow)
            <tr>
                <td>
                    <a onclick="showWorkflow(`{{$workflow->workflow}}`, '{{$workflow->name}}')"
                       href="#">{{$workflow->name}}</a>
                </td>
                <td>{{$workflow->description}}</td>
                <td>{{$workflow->updated_at->diffForHumans()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
    <h3 id="workflowtitle"></h3>
    <div id="workflow"></div>
</div>

@push('scripts')
    <script>
        let chart;
        $(document).ready(() => {
            $('#workflows').DataTable({
                stateSave: true,
                pageLength: 4,
                orderFixed: [0, 'desc'],
                lengthMenu: [4],
            });
            @if(sizeof($dataset->workflows) !== 0)
            showWorkflow(`{!!$dataset->workflows[0]->workflow!!}`, '{{$dataset->workflows[0]->name}}');
            @endif
        });

        function showWorkflow(code, name) {
            if (chart) {
                chart.clean();
            }
            $("#workflowtitle").html(name);
            let fl = simplefl.parseSimpleFlowchart(code);
            chart = mcfl.drawFlowchart('workflow', fl);
        }
    </script>
@endpush