<ul>
    @foreach($activity->entityStates as $es)
        @if($es->pivot->direction == "out")
            @foreach($es->attributes as $attr)
                <li>
                    {{$attr->name}}: {{$attr->values[0]->val["value"]}}
                    @if($attr->values[0]->unit != "")
                        {{$attr->values[0]->unit}}
                    @endif
                </li>
            @endforeach
        @endif
    @endforeach
</ul>