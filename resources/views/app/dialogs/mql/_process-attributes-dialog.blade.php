<div class="modal fade" tabindex="-1" id="mql-process-attributes-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Process Attributes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <ul class="list-unstyled ml-4">
                    <h4>Process Attributes</h4>
                    @foreach($processAttributes as $attr)
                        <li class="col-12">
                            <div class="row">
                                <input type="checkbox" class="mr-2" name="[]process_attrs" value="{{$attr->name}}">
                                {{$attr->name}}
                            </div>
                            <div class="row ml-3">
                                <select class="selectpicker col-5">
                                    <option>choose operation</option>
                                    <option>=</option>
                                    <option>></option>
                                    <option>>=</option>
                                    <option><</option>
                                    <option><=</option>
                                    <option><></option>
                                </select>
                                <input type="text" placeholder="Value...">
                            </div>
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