<div class="modal fade" tabindex="-1" id="copy-choose-project-dialog" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Select Project To Copy To/From</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select id="project-select" class="selectpicker col-lg-10" data-live-search="true"
                        title="Select Project To Copy To/From">
                    <option data-tokens="{{$project->id}}" value="{{$project->id}}"
                            data-root-id="{{$project->rootDir->id}}">
                        {{$project->name}} (Current Project)
                    </option>
                    @foreach($projects as $p)
                        @if($p->id != $project->id)
                            @if(isset($p->rootDir))
                                <option data-tokens="{{$p->id}}" value="{{$p->id}}" data-root-id="{{$p->rootDir->id}}">
                                    {{$p->name}} ({{$p->owner->name}})
                                </option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#project-select').on('change', () => {
                let selected = '.selectpicker option:selected';
                let selectedProjectId = $(selected).val();
                let selectedProjectRootDirId = $(selected).attr('data-root-id')
                console.log("selected = '" + selectedProjectId + "'");
                console.log("dir-id = '" + selectedProjectRootDirId + "'");
                window.location.href = route('projects.folders.show-for-copy', {
                    'leftProject': "{{$project->id}}",
                    'leftFolder': "{{$directory->id}}",
                    'rightProject': selectedProjectId,
                    'rightFolder': selectedProjectRootDirId,
                });
            });
        });
    </script>
@endpush