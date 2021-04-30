<div class="modal fade" tabindex="-1" id="code-dialog" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Code for Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                 <pre>
import materials_commons.api as mcapi
c = mcapi.Client.("{{auth()->user()->api_token}}", base_url="https://materialscommons.org/api")
proj = c.get_project(77)
proj.pretty_print()
                </pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>