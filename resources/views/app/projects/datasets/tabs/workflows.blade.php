@component('components.card')
    @slot('header')
        Workflows
    @endslot

    @slot('body')
        <table id="workflows" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dataset->workflows as $workflow)
                <tr>
                    <td>
                        <a href="#" onclick="showWorkflow(`{{$workflow->workflow}}`, '{{$workflow->name}}')">
                            {{$workflow->name}}
                        </a>
                    </td>
                    <td>{{$workflow->description}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <h3 id="workflowtitle"></h3>
        <div id="workflow"></div>
    @endslot
@endcomponent

@push('scripts')
    <script>
        let chart;
        $(document).ready(function () {
            $(document).ready(() => {
                $('#workflows').DataTable({
                    stateSave: true,
                    pageLength: 4,
                    orderFixed: [0, 'desc'],
                    lengthMenu: [4],
                });
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