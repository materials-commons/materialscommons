@php
    $attributesCount = 0;
@endphp
<div class="row ml-2">
    @foreach($activity->entityStates as $es)
        @if($es->pivot->direction == "out")
            @foreach($es->attributes as $attr)
                @php
                    $attributesCount++;
                @endphp
                <div class="attribute-row row col-11 ml-1">
                    <div class="col-7">{{$attr->name}}:</div>
                    <div class="col-5">
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
                    </div>
                </div>
            @endforeach
        @endif
    @endforeach
    @if($attributesCount == 0)
        <span class="ml-1">No Attributes</span>
    @endif
</div>
