<div class="modal" tabindex="-1" id="reload-experiment-modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Reload Study</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(!is_null($experiment->sheet))
                    You have an existing Google sheet you last loaded the study from:
                    <a href="{{$experiment->sheet->url}}" target="_blank">{{$experiment->sheet->title}}</a>.
                @else
                    <div>
                        <h5>
                            You have an existing excel file you lasted loaded the study from:
                            <a href="{{route('projects.files.by-path', [$project, 'path' => $experiment->loaded_file_path])}}"
                               class="no-underline">{{$experiment->loaded_file_path}}</a>
                        </h5>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="{{route('projects.experiments.show-reload', [$project, $experiment])}}"
                   class="btn btn-info">Reload from different source</a>
                <form method="post" action="{{route('projects.experiments.reload', [$project, $experiment])}}">
                    @csrf
                    @method('put')
                    @if(!is_null($experiment->sheet))
                        <input type="hidden" name="sheet_id" value="{{$experiment->sheet->id}}"/>
                    @else
                        <input type="hidden" name="file_path" value="{{$experiment->loaded_file_path}}"/>
                    @endif
                    <button type="submit" class="btn btn-success">Reload</button>
                </form>
            </div>
        </div>
    </div>
</div>