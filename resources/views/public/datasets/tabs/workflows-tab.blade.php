@include('public.datasets.tabs._short-overview')
<div class="ms-2">
    <table id="workflows" class="table table-hover">
        <thead>
        <tr>
            <th>Workflow</th>
            <th>Description</th>
            <th>Updated</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($workflows as $workflow)
            <tr>
                <td>
                    <a onclick="showWorkflowForWorkflowsTab(`{{$workflow->workflow}}`, '{{$workflow->name}}')"
                       href="#">{{$workflow->name}}</a>
                </td>
                <td>{{$workflow->description}}</td>
                <td>{{$workflow->updated_at->diffForHumans()}}</td>
                <td>{{$workflow->updated_at}}</td>
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
        $(document).ready(() => {
            $('#workflows').DataTable({
                stateSave: true,
                pageLength: 4,
                order: [[0, 'desc']],
                lengthMenu: [4],
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
            @if(sizeof($workflows) !== 0)
            showWorkflowForWorkflowsTab(`{!!$workflows->values()->get(0)->workflow!!}`, '{{$workflows->values()->get(0)->name}}');
            @endif
        });

        if (typeof showWorkflowForWorkflowsTab === 'undefined') {
            let chart;

            function showWorkflowForWorkflowsTab(code, name) {
                if (chart) {
                    chart.clean();
                }
                $("#workflowtitle").html(name);
                let fl = simplefl.parseSimpleFlowchart(code);
                chart = mcfl.drawFlowchart('workflow', fl);
            }
        }
    </script>
@endpush
