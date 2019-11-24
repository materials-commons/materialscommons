<div class="ml-2">
    <div class="float-right">
        <a href="{{route('projects.experiments.workflows.create', [$project, $experiment])}}" class="action-link">
            <i class="fas fa-fw fa-plus"></i>
        </a>
        <a href="#" onclick="toggleCodeArea()" class="action-link">
            <i class="fas fa-fw fa-edit"></i>
        </a>
        <a data-toggle="modal" href="#project-delete-{{$project->id}}" class="action-link">
            <i class="fas fa-fw fa-trash-alt"></i>
        </a>
    </div>
    <table id="workflows" class="table table-hover">
        <thead>
        <tr>
            <th>Workflow</th>
            <th>Description</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($experiment->workflows as $workflow)
            <tr>
                <td>
                    <a onclick="showSelectedWorkflow(`{{$workflow->workflow}}`, '{{$workflow->name}}')"
                       href="#">{{$workflow->name}}</a>
                </td>
                <td>{{$workflow->description}}</td>
                <td>{{$workflow->updated_at->diffForHumans()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
    @if(sizeof($experiment->workflows) !== 0)
        <h3 id="workflowtitle">{{$experiment->workflows[0]->name}}</h3>
    @endif
    <div id="workflow"></div>
    <div id="codearea" class="col-lg-10" hidden>
        <br>

        <form method="post"
              @if (sizeof($experiment->workflows) !== 0)
              action="{{route('projects.experiments.workflows.update', [$project->id, $experiment->id, $experiment->workflows[0]->id])}}"
              @else
              action="#"
              @endif
              id="edit-workflow">

            @csrf
            @method('patch')
            <input hidden name="project_id" value="{{$project->id}}">
            <div class="form-group">
                <label for="description">Workflow</label>
                @if (sizeof($experiment->workflows) !== 0)
                    <textarea id="code" style="width:100%" rows="20"
                              name="workflow">{{$experiment->workflows[0]->workflow}}</textarea>
                @else
                    <textarea id="code" style="width:100%" rows="20" name="workflow"></textarea>
                @endif
            </div>
            <div class="float-right">
                <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
                <button class="btn btn-success">Save</button>
                <button type="button" onclick="resetAndClose()" class="btn btn-warning">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let chart, savedCode;
        $(document).ready(() => {
            let count = {!! sizeof($experiment->workflows) !!};
            if (count !== 0) {
                drawWorkflow();
            }
            savedCode = document.getElementById('code').value;

            $('#workflows').DataTable({
                stateSave: true,
                pageLength: 4,
            });
        });

        function showSelectedWorkflow(code, name) {
            if (chart) {
                chart.clean();
            }
            $("#workflowtitle").html(name);
            let fl = simplefl.parseSimpleFlowchart(code);
            chart = mcfl.drawFlowchart('workflow', fl);
        }

        function drawWorkflow() {
            if (chart) {
                chart.clean();
            }
            let code = document.getElementById('code').value;
            let fl = simplefl.parseSimpleFlowchart(code);
            chart = mcfl.drawFlowchart('workflow', fl);
        }

        function toggleCodeArea() {
            let el = document.getElementById('codearea');
            if (el.getAttribute('hidden') !== null) {
                el.removeAttribute('hidden');
            } else {
                el.setAttribute('hidden', '');
            }
        }

        function resetAndClose() {
            document.getElementById('code').value = savedCode;
            drawWorkflow();
            toggleCodeArea();
        }
    </script>
@endpush