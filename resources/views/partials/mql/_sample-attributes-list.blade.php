<ul class="list-unstyled ml-4">
    @foreach($sampleAttributes as $attr)
        <li class="col-12">
            @if(old('sample_attrs') && isset(old('sample_attrs')[$loop->index]['name']))
                <div class="row">
                    <input type="checkbox" class="mr-2" name="sample_attrs[{{$loop->index}}][name]"
                           value="{{$attr->name}}" checked>
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route('projects.entities.attributes.show-details-by-name', [$project, $attr->name])}}">{{$attr->name}}</a>
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
                    <a href="#"
                       hx-target="#{{slugify($attr->name)}}"
                       hx-swap="innerHTML"
                       hx-get="{{route('projects.entities.attributes.show-details-by-name', [$project, $attr->name])}}">{{$attr->name}}</a>
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
            <div id="{{slugify($attr->name)}}" class="row ml-4 mt-2"></div>
        </li>
    @endforeach
</ul>