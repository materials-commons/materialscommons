<ul class="list-unstyled ml-4">
    @foreach($attrs as $attr)
        <li class="col-12 mt-2">
            @if(old($formVarName) && isset(old($formVarName)[$loop->index]['name']))
                <div class="row">
                    <input type="checkbox" class="mr-1" name="{{$formVarName}}[{{$loop->index}}][name]"
                           value="{{$attr->name}}" checked>
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route($detailsRouteName, [$project, $attr->name])}}">{{$attr->name}}</a>
                </div>
                <div class="row ml-1">
                    <select class="selectpicker col-6"
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
                           value="{{old($formVarName)[$loop->index]['value']}}">
                </div>
            @else
                <div class="row">
                    <input type="checkbox" class="mr-1" name="{{$formVarName}}[{{$loop->index}}][name]"
                           value="{{$attr->name}}">
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route($detailsRouteName, [$project, $attr->name])}}">
                        {{$attr->name}}
                    </a>
                </div>
                <div class="row ml-1">
                    <select class="selectpicker col-6" name="{{$formVarName}}[{{$loop->index}}][operator]">
                        <option>Select</option>
                        <option>=</option>
                        <option>></option>
                        <option>>=</option>
                        <option><</option>
                        <option><=</option>
                        <option><></option>
                    </select>
                    <input type="text" placeholder="Value..." class="col-4"
                           name="{{$formVarName}}[{{$loop->index}}][value]">
                </div>
            @endif
            <div id="{{slugify($attr->name)}}" class="row ml-4 mt-2"></div>
        </li>
    @endforeach
</ul>