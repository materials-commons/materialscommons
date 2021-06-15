<div class="modal fade" tabindex="-1" id="mql-sample-attributes-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Sample Attributes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" stylex="height: 500px">
                <h4>Sample Attributes</h4>
                <ul class="list-unstyled ml-4">
                    @foreach($sampleAttributes as $attr)
                        <li>
                        <li class="col-12">
                            <div class="row">
                                <input type="checkbox" class="mr-2" name="[]sample_attrs" value="{{$attr->name}}">
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