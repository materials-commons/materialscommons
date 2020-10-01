<dl class="row ml-2">
    @foreach($activity->entityStates as $es)
        @if($es->pivot->direction == "out")
            @foreach($es->attributes as $attr)
                <dt class="col-7">{{$attr->name}}:</dt>
                <dd class="col-4">
                    @if(is_array($attr->values[0]->val["value"]))
                        @json($attr->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                    @else
                        @if(blank($attr->values[0]->val["value"]))
                            No value
                        @else
                            {{$attr->values[0]->val["value"]}}
                        @endif
                    @endif
                    @if($attr->values[0]->unit != "")
                        {{$attr->values[0]->unit}}
                    @endif
                </dd>
            @endforeach
        @endif
    @endforeach
</dl>