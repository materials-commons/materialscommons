<ul class="list-unstyled ms-4">
    @foreach($attrs as $attr)
        <li class="col-12 mt-2">
            @if(old($formVarName) && isset(old($formVarName)[$loop->index]['name']))
                <div class="row">
                    <input type="checkbox" class="me-1" name="{{$formVarName}}[{{$loop->index}}][name]"
                           value="{{$attr->name}}" checked>
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route($detailsRouteName, [$project, $attr->name])}}">{{$attr->name}}</a>
                </div>
                <div class="row ms-1">
                    <select class="form-select"
                            name="{{$formVarName}}[{{$loop->index}}][operator]"
                            value="{{old($formVarName)[$loop->index]['operator']}}">
                        <option>Select</option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '=' ? 'selected' : ''}}>
                            =
                        </option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '>' ? 'selected' : ''}}>
                            >
                        </option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '>=' ? 'selected' : ''}}>
                            >=
                        </option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '<' ? 'selected' : ''}}>
                            <
                        </option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '<=' ? 'selected' : ''}}>
                            <=
                        </option>
                        <option {{old($formVarName)[$loop->index]['operator'] == '<>' ? 'selected' : ''}}>
                            <>
                        </option>
                    </select>
                    <input type="text" placeholder="Value..." class="col-4"
                           name="{{$formVarName}}[{{$loop->index}}][value]"
                           value="{{old($formVarName)[$loop->index]['value']}}"
                           hx-post="{{route('projects.entities.mql.show', $project)}}"
                           hx-include="#mql-selection"
                           hx-target="#mql-query"
                           hx-trigger="keyup changed delay:500ms">
                </div>
            @else
                <div class="row">
                    <input type="checkbox" class="me-1" name="{{$formVarName}}[{{$loop->index}}][name]"
                           value="{{$attr->name}}"
                           hx-post="{{route('projects.entities.mql.show', $project)}}"
                           hx-include="#mql-selection"
                           hx-target="#mql-query"
                           hx-trigger="click">
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route($detailsRouteName, [$project, $attr->name])}}">
                        {{$attr->name}}
                    </a>
                </div>
                <div class="row ms-1">
                    <select id="select-{{$loop->index}}"
                            class="selectpicker col-6"
                            name="{{$formVarName}}[{{$loop->index}}][operator]" value=""
                            onchange="fireEvent('#select-{{$loop->index}}')"
                            hx-post="{{route('projects.entities.mql.show', $project)}}"
                            hx-include="#mql-selection"
                            hx-target="#mql-query"
                            hx-trigger="changed.bs.select">
                        <option>Select</option>
                        <option>=</option>
                        <option>></option>
                        <option>>=</option>
                        <option><</option>
                        <option><=</option>
                        <option><></option>
                    </select>
                    <input type="text" placeholder="Value..." class="col-4"
                           name="{{$formVarName}}[{{$loop->index}}][value]"
                           value=""
                           hx-post="{{route('projects.entities.mql.show', $project)}}"
                           hx-include="#mql-selection"
                           hx-target="#mql-query"
                           hx-trigger="keyup changed delay:500ms">
                </div>
            @endif
            <div id="{{slugify($attr->name)}}" class="row ms-4 mt-2"></div>
        </li>
    @endforeach

        @push('scripts')
            <script>
                if (typeof fireEvent === 'undefined') {
                    function fireEvent(id) {
                        let event = new Event('changed.bs.select');
                        document.querySelector(id).dispatchEvent(event);
                    }
                }
            </script>
        @endpush
</ul>
