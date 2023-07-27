<div class="modal fade" tabindex="-1" id="mql-save-query-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Save Query</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <h4>Save Query</h4>
                <form id="mql-save">
                    <div class="form-group">
                        <label>Query</label>
                        <textarea class="form-control" name="query_text" id="query-text">{{$query}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" value="" type="text"
                               placeholder="Name..." required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" type="text"
                                  placeholder="Description..." required></textarea>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-danger" href="#" data-dismiss="modal">Cancel</a>
                        <a class="btn btn-primary" data-dismiss="modal"
                           hx-post="{{route('projects.mql.store', $project)}}" hx-include="#mql-selection">
                            Save
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>