<div class="ml-2">
    <div class="float-right">
        <a href="{{route('projects.experiments.workflows.create', [$project, $experiment])}}" class="action-link">
            <i class="fas fa-fw fa-plus"></i>
        </a>
    </div>
    <table id="workflows" class="table table-hover">
        <thead>
        <tr>
            <th>Workflow</th>
            <th>Description</th>
            <th>Updated</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($experiment->workflows as $workflow)
            <tr>
                <td>
                    <a onclick="showWorkflow(`{{$workflow->workflow}}`, '{{$workflow->name}}')"
                       href="#">{{$workflow->name}}</a>
                </td>
                <td>{{$workflow->description}}</td>
                <td>{{$workflow->updated_at->diffForHumans()}}</td>
                <td>
                    <a href="{{route('projects.experiments.workflows.edit', [$project, $experiment, $workflow])}}"
                       class="action-link">
                        <i class="fas fa-fw fa-edit"></i>
                    </a>
                </td>
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
            @if(sizeof($experiment->workflows) !== 0)
            showWorkflow(`{!!$experiment->workflows[0]->workflow!!}`, '{{$experiment->workflows[0]->name}}');
            @endif
        });

        function showWorkflow(code, name) {
            console.log(`showWorkflow ${code}`);
            if (chart) {
                chart.clean();
            }
            $("#workflowtitle").html(name);
            let fl = simplefl.parseSimpleFlowchart(code);
            chart = mcfl.drawFlowchart('workflow', fl);
        }
    </script>
@endpush