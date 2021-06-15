<div class="modal fade" tabindex="-1" id="mql-processes-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Processes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <h4>Processes</h4>
                <ul class="list-unstyled ml-4">
                    @foreach($activities as $activity)
                        <li>
                            <input type="checkbox" name="[]activities" value="{{$activity->name}}">
                            {{$activity->name}}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>