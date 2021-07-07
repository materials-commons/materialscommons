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
                @if(false)
                    <a href="#" class="mb-2">Close Summary Table</a>
                    <table class="table table-hover mt-2">
                        <thead>
                        <th>Attribute</th>
                        <th>Numeric?</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th># Unique Values</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Beam Intensity or current</td>
                            <td>Yes</td>
                            <td>20</td>
                            <td>20</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>Black Pre-Heat Temperature</td>
                            <td>Yes</td>
                            <td>100</td>
                            <td>300</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>No</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>More</td>
                            <td>Not</td>
                            <td>Shown</td>
                            <td>For</td>
                            <td>Example....</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                <ul class="list-unstyled ml-4">
                    <h4>Process Attributes</h4>
                    @foreach($processAttributes as $attr)
                        <li class="col-12 mt-2">
                            @if(old('process_attrs') && isset(old('process_attrs')[$loop->index]['name']))
                                <div class="row">
                                    <input type="checkbox" class="mr-2" name="process_attrs[{{$loop->index}}][name]"
                                           value="{{$attr->name}}" checked>
                                    <a href="#"
                                       hx-target="#{{slugify($attr->name)}}"
                                       hx-swap="innerHTML"
                                       hx-get="{{route('projects.activities.attributes.show-details-by-name', [$project, $attr->name])}}">{{$attr->name}}</a>
                                </div>
                                <div class="row ml-3">
                                    <select class="selectpicker col-5"
                                            name="process_attrs[{{$loop->index}}][operator]"
                                            value="{{old('process_attrs')[$loop->index]['operator']}}">
                                        <option>choose operator</option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '=' ? 'selected' : ''}}>
                                            =
                                        </option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '>' ? 'selected' : ''}}>
                                            >
                                        </option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '>=' ? 'selected' : ''}}>
                                            >=
                                        </option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '<' ? 'selected' : ''}}>
                                            <
                                        </option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '<=' ? 'selected' : ''}}>
                                            <=
                                        </option>
                                        <option {{old('process_attrs')[$loop->index]['operator'] == '<>' ? 'selected' : ''}}>
                                            <>
                                        </option>
                                    </select>
                                    <input type="text" placeholder="Value..."
                                           name="process_attrs[{{$loop->index}}][value]"
                                           value="{{old('process_attrs')[$loop->index]['value']}}">
                                </div>
                            @else
                                <div class="row">
                                    <input type="checkbox" class="mr-2" name="process_attrs[{{$loop->index}}][name]"
                                           value="{{$attr->name}}">
                                    <a href="#"
                                       hx-target="#{{slugify($attr->name)}}"
                                       hx-swap="innerHTML"
                                       hx-get="{{route('projects.activities.attributes.show-details-by-name', [$project, $attr->name])}}">
                                        {{$attr->name}}
                                    </a>
                                </div>
                                <div class="row ml-3">
                                    <select class="selectpicker col-5" name="process_attrs[{{$loop->index}}][operator]">
                                        <option>choose operator</option>
                                        <option>=</option>
                                        <option>></option>
                                        <option>>=</option>
                                        <option><</option>
                                        <option><=</option>
                                        <option><></option>
                                    </select>
                                    <input type="text" placeholder="Value..."
                                           name="process_attrs[{{$loop->index}}][value]">
                                </div>
                            @endif
                            <div id="{{slugify($attr->name)}}" class="row ml-4 mt-2"></div>
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