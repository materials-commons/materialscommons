<div class="modal fade" tabindex="-1" id="select-script-dialog" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Select Script To Run</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Script</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($scripts as $script)
                        <tr>
                            <td>
                                {{$script->scriptFile->fullPath()}}
                            </td>
                            <td>
                                Not available yet
                            </td>
                            <td>
                                <a class="action-link float-right mr-4"
                                   onclick="$('#select-script-dialog').modal('hide')"
                                   href="{{route('projects.files.run-script-with-folder-context', [$project, $directory, $script->scriptFile])}}">
                                    <i class="fas fa-fw fa-play-circle mr-2"></i>Run
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Run</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>