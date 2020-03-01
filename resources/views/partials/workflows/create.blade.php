<form method="post" action="{{$storeRoute}}">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" placeholder="Workflow Name...">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description"
                  placeholder="Description..."></textarea>
    </div>

    <div class="form-group">
        <label for="workflowcode">Workflow</label>
        <textarea class="form-control" id="workflowcode" name="workflow"
                  placeholder="Workflow...">{{isset($workflowcode) ? $workflowcode : ''}}</textarea>
    </div>

    <div class="float-right">
        <button type="button" onclick="drawWorkflow()" class="btn btn-info">Run</button>
        <button class="btn btn-success">Save</button>
        <button type="button" onclick="cancel()" class="btn btn-warning">Cancel</button>
    </div>
</form>
<div id="workflowcanvas"></div>

@include('partials.workflows.help')
@include('partials.workflows.workflow_js', ['cancelRoute' => $cancelRoute])