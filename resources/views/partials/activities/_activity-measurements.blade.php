<ul>
    @foreach($activity->entityStates as $es)
        @if($es->pivot->direction == "out")
            @foreach($es->attributes as $attr)
                <li>
                    {{$attr->name}}
                    : @json($attr->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                    @if($attr->values[0]->unit != "")
                        {{$attr->values[0]->unit}}
                    @endif
                </li>
            @endforeach
        @endif
    @endforeach
</ul>