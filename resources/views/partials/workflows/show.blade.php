<div id="codearea" class="col-lg-10">
    <form id="edit-workflow">
        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{$workflow->name}}"
                   placeholder="Workflow Name..." readonly>
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"
                      placeholder="Description..." readonly>{{$workflow->description}}</textarea>
        </div>

        <div class="mb-3">
            <label for="workflowcode">Workflow</label>
            <textarea id="workflowcode" class="form-control"
                      name="workflow" readonly>{{$workflow->workflow}}</textarea>
        </div>
        <div class="float-end">
            <a href="#" class="action-link" onclick="window.history.back()">
                Done
            </a>
        </div>
    </form>
</div>
<br>
<div id="workflowcanvas"></div>

@include('partials.workflows.workflow_js')

@push('scripts')
    <script>
        $(document).ready(() => {
            drawWorkflow();
        });
    </script>
@endpush