<div class="ml-2">
    <div class="float-right">
        <a href="{{route('projects.show', ['id' => $project->id])}}" class="action-link">
            <i class="fas fa-fw fa-plus"></i>
        </a>
        <a href="#" onclick="toggleCodearea()" class="action-link">
            <i class="fas fa-fw fa-edit"></i>
        </a>
        <a data-toggle="modal" href="#project-delete-{{$project->id}}" class="action-link">
            <i class="fas fa-fw fa-trash-alt"></i>
        </a>
    </div>
    <div id="workflow"></div>
    <div id="codearea" class="col-lg-10">
        <br>
        <form method="post"
              action="{{route('projects.experiments.workflows.update', [$project->id, $experiment->id, $experiment->workflows[0]->id])}}"
              id="edit-workflow">
            @csrf
            @method('patch')
            <input hidden name="project_id" value="{{$project->id}}">
            <div class="form-group">
                <label for="description">Workflow</label>
                @if ($experiment->workflows()->count() !== 0)
                    <textarea id="code" style="width:100%" rows="20"
                              name="workflow">{{$experiment->workflows[0]->workflow}}</textarea>
                @else
                    <textarea id="code" style="width:100%" rows="20" name="workflow"></textarea>
                @endif
            </div>
            <div class="float-right">
                <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
                <button class="btn btn-success">Save
                </button>
                <button type="button" onclick="" class="btn btn-warning">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let chart;
        $(document).ready(() => {
            let count = {!! $experiment->workflows()->count() !!};
            if (count !== 0) {
                drawWorkflow();
            }
        });

        function drawWorkflow() {
            if (chart) {
                chart.clean();
            }
            let code = document.getElementById('code').value;
            chart = mcfl.drawFlowchart('workflow', code);
        }

        function toggleCodearea() {
            let el = document.getElementById('codearea');
            if (el.getAttribute('hidden') !== null) {
                el.removeAttribute('hidden');
            } else {
                el.setAttribute('hidden', '');
            }
        }
    </script>
@endpush