<div class="modal" tabindex="-1" id="project-delete-{{$project->id}}" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Immediately Delete Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Immediately delete project {{$project->name}}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="{{route('dashboard.projects.trash.immediately-destroy', $project->id)}}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Immediately Delete Project</button>
                </form>
            </div>
        </div>
    </div>
</div>