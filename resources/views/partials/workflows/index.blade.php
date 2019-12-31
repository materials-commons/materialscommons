<table id="workflows" class="table table-hover">
    <thead>
    <tr>
        <th>Workflow</th>
        <th>Description</th>
        <th>Updated</th>
        @if(isset($editExperimentWorkflowRoute))
            <th></th>
        @elseif(isset($editProjectWorkflowRoute))
            <th></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($workflows as $workflow)
        <tr>
            <td>
                <a onclick="showWorkflow(`{{$workflow->workflow}}`, '{{$workflow->name}}')"
                   href="#">{{$workflow->name}}</a>
            </td>
            <td>{{$workflow->description}}</td>
            <td>{{$workflow->updated_at->diffForHumans()}}</td>
            @if(isset($editExperimentWorkflowRoute))
                <td>
                    <a href="{{route($editExperimentWorkflowRoute, [$project, $experiment, $workflow])}}"
                       class="action-link">
                        <i class="fas fa-fw fa-edit"></i>
                    </a>
                </td>
            @elseif(isset($editProjectWorkflowRoute))
                <td></td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<h3 id="workflowtitle"></h3>
<div id="workflow"></div>

@push('scripts')
    <script>
        let chart;
        $(document).ready(() => {
            $('#workflows').DataTable({
                stateSave: true,
                pageLength: 4,
                order: [[0, 'desc']],
                lengthMenu: [4],
            });
            @if(sizeof($workflows) !== 0)
            showWorkflow(`{!!$workflows->values()->get(0)->workflow!!}`, '{{$workflows->values()->get(0)->name}}');
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