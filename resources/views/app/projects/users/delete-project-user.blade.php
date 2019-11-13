<div class="modal" tabindex="-1" id="user-delete-{{$user->id}}" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove User From Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Remove user {{$user->name}} from project?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="{{route('projects.users.remove', [$project, $user])}}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Remove User</button>
                </form>
            </div>
        </div>
    </div>
</div>