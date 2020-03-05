<div class="modal fade" tabindex="-1" id="help-dialog" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Help for {{helpTitle()}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <iframe src="{{helpUrl()}}" width="100%" height="100%"></iframe>
            </div>
            <div class="modal-footer">
                <a class="btn btn-info" data-toggle="modal" data-dismiss="modal" href="#welcome-dialog">Welcome
                    Dialog!</a>
                <a class="btn btn-secondary" href="{{helpGettingStarted()}}" target="_blank">Goto Docs</a>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>