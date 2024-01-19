<div class="modal" tabindex="-1" id="empty-trash-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Empty Trash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to empty the trash? This operation cannot be undone and all items in the trash
                    will be deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="{{route('projects.trashcan.empty', [$project])}}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">yes empty trash</button>
                </form>
            </div>
        </div>
    </div>
</div>