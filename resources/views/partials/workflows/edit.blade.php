<div id="codearea" class="col-lg-10">
    <form method="post" action="{{$updateRoute}}" id="edit-workflow">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{$workflow->name}}"
                   placeholder="Workflow Name...">
        </div>
        <div class="form-group">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" value="{{$workflow->summary}}" name="summary">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"
                      placeholder="Description...">{{$workflow->description}}</textarea>
        </div>

        <div class="form-group">
            <label for="workflowcode">Workflow</label>
            <textarea id="workflowcode" class="form-control"
                      name="workflow">{{$workflow->workflow}}</textarea>
        </div>
        <div class="float-right">
            <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
            <button class="btn btn-success">Save</button>
            <button type="button" onclick="cancel()" class="btn btn-warning">Cancel</button>
        </div>

        <input hidden name="project_id" value="{{$project->id}}">
    </form>
</div>
<br>
<div id="workflowcanvas"></div>

@include('partials.workflows.help')
@include('partials.workflows.workflow_js', ['cancelRoute' => $cancelRoute])

@push('scripts')
    <script>
        $(document).ready(() => {
            drawWorkflow();
        });
    </script>
@endpush