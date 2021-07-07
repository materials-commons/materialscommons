<div class="modal fade" tabindex="-1" id="mql-process-attributes-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Process Attributes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <h4>Process Attributes</h4>
                @include('partials.mql._process-attributes-list')
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" data-dismiss="modal"
                   hx-post="{{route('projects.entities.mql.show', $project)}}"
                   hx-include="#mql-selection"
                   hx-target="#mql-query">
                    Done
                </a>
            </div>
        </div>
    </div>
</div>