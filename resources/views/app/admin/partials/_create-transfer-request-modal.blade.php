<div class="modal" tabindex="-1" id="create-transfer-request-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Transfer Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Create New Transfer Request For Project</p>
                <form id="create-transfer-request-form" method="post"
                      action="{{route('admin.dashboard.mcfs.transfer-requests.create')}}">
                    @csrf
                    <div class="form-group">
                        <label>Find Project</label>
                        <input class="form-control" name="project_name" value=""
                               hx-get="{{route('htmx.searchers.find-project')}}"
                               hx-target="#matching-projects"
                               hx-indicator="#project-indicator"
                               hx-trigger="keyup changed delay:500ms"
                               type="text" placeholder="Find project...">
                    </div>
                    <span id="project-indicator" class="htmx-indicator"><i class="fas fa-spinner fa-spin"></i></span>
                    <div id="matching-projects"></div>
                    <div class="form-group">
                        <label>Find User</label>
                        <input class="form-control" name="user_name" value=""
                               hx-get="{{route('htmx.searchers.find-user')}}"
                               hx-target="#matching-users"
                               hx-indicator="#user-indicator"
                               hx-trigger="keyup changed delay:500ms"
                               type="text" placeholder="Find user...">
                    </div>
                    <span id="user-indicator" class="htmx-indicator"><i class="fas fa-spinner fa-spin"></i></span>
                    <div id="matching-users"></div>
                    <hr/>
                    <div class="form-check">
                        <input type="hidden" name="is_mc_transfer" value="0"/>
                        <input class="form-check-input" name="is_mc_transfer" value="1"
                               type="checkbox" id="is-mc-transfer">
                        <label class="form-check-label" for="is-mc-transfer">Create As MC Transfer</label>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label>Project ID</label>
                        <input class="form-control" name="project_id" value=""
                               type="text" placeholder="Project ID..." required>
                    </div>
                    <div class="form-group">
                        <label>Email of Owner</label>
                        <input class="form-control" name="email" value="" type="email" placeholder="Email..." required>
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a onclick="document.getElementById('create-transfer-request-form').submit()"
                           class="btn btn-primary"
                           type="submit" data-dismiss="modal">Create Transfer Request</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>