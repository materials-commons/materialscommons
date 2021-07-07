<ul class="list-unstyled ml-4">
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