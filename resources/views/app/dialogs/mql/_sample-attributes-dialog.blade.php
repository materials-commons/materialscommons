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
                        <li class="col-12">
                            @if(old('sample_attrs') && isset(old('sample_attrs')[$loop->index]['name']))
                                <div class="row">
                                    <input type="checkbox" class="mr-2" name="sample_attrs[{{$loop->index}}][name]"
                                           value="{{$attr->name}}" checked>
                                    {{$attr->name}}
                                </div>
                                <div class="row ml-3">
                                    <select class="selectpicker col-5"
                                            name="sample_attrs[{{$loop->index}}][operator]"
                                            value="{{old('sample_attrs')[$loop->index]['value']}}">
                                        <option>choose operator</option>
                                        <option>=</option>
                                        <option>></option>
                                        <option>>=</option>
                                        <option><</option>
                                        <option><=</option>
                                        <option><></option>
                                    </select>
                                    <input type="text" placeholder="Value..."
                                           name="sample_attrs[{{$loop->index}}][value]"
                                           value="{{old('process_attrs')[$loop->index]['value']}}">
                                </div>
                            @else
                                <div class="row">
                                    <input type="checkbox" class="mr-2" name="sample_attrs[{{$loop->index}}][name]"
                                           value="{{$attr->name}}">
                                    {{$attr->name}}
                                </div>
                                <div class="row ml-3">
                                    <select class="selectpicker col-5" name="sample_attrs[{{$loop->index}}][operator]">
                                        <option>choose operator</option>
                                        <option>=</option>
                                        <option>></option>
                                        <option>>=</option>
                                        <option><</option>
                                        <option><=</option>
                                        <option><></option>
                                    </select>
                                    <input type="text" placeholder="Value..."
                                           name="sample_attrs[{{$loop->index}}][value]">
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" data-dismiss="modal"
                   hx-post="{{route('projects.entities.mql.show', $project)}}"
                   hx-include="#mql-selection"
                   hx-target="#mql-query">
                    Done
                </a>
            </div>
        </div>
    </div>
</div>