<div class="modal" tabindex="-1" id="set-log-level-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Logging Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="set-logging-level-form" method="post"
                      action="{{$setLogLevelRoute}}">
                    @csrf
                    <div class="mb-3">
                        <label for="level">Select Logging Level</label>
                        <select name="level" class="selectpicker" title="level"
                                data-style="btn-light no-tt">
                            <option value=""></option>
                            <option value="debug">DEBUG</option>
                            <option value="info">INFO</option>
                            <option value="warn">WARN</option>
                            <option value="error">ERROR</option>
                            <option value="fatal">FATAL</option>
                        </select>
                    </div>

                    <div class="float-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a onclick="document.getElementById('set-logging-level-form').submit()"
                           class="btn btn-primary"
                           type="submit" data-bs-dismiss="modal">Set Logging Level</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
