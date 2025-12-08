<div class="modal fade" tabindex="-1" id="select-script-dialog" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Select Script To Run</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Script</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($scripts as $script)
                        <tr>
                            <td>
                                {{$script->fullPath()}}
                            </td>
                            <td>
                                <a class="action-link float-end me-4"
                                   onclick="$('#select-script-dialog').modal('hide')"
                                   href="{{route('projects.files.run-script-with-folder-context', [$project, $directory, $script])}}">
                                    <i class="fas fa-fw fa-play-circle me-2"></i>Run
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
