<div class="mt-2">
    <h5>{{$activity->name}}</h5>
    <ul>
        @foreach($activity->attributes as $attribute)
            <li>
                {{$attribute->name}}
                : @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                @if($attribute->values[0]->unit != "")
                    ({{$attribute->values[0]->unit}})
                @endif
            </li>
        @endforeach
    </ul>
    <h6>Measurements</h6>
    <ul>
        @foreach($activity->entityStates as $es)
            @if($es->pivot->direction == "out")
                @foreach($es->attributes as $attr)
                    <li>
                        {{$attr->name}}
                        : @json($attr->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                        @if($attr->values[0]->unit != "")
                            ({{$attr->values[0]->unit}})
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach
    </ul>
    <h6>Files</h6>
    <ul>
        @foreach($activity->files as $f)
            <li>{{$file->name}}</li>
        @endforeach
    </ul>
</div>