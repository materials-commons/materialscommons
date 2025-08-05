@php
    $attributesCount = 0;
@endphp
<div class="row ml-2">
    @foreach($activity->attributes as $attribute)
        @php
            $attributesCount++;
        @endphp
        <div class="attribute-row row col-11 ml-1">
            <div class="col-7">{{$attribute->name}}:</div>
            <div class="col-5">
                @if(is_array($attribute->values[0]->val["value"]))
                    @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                @else
                    @if(blank($attribute->values[0]->val["value"]))
                        No value
                    @else
                        {{$attribute->values[0]->val["value"]}}
                    @endif
                @endif
                @if($attribute->values[0]->unit != "")
                    {{$attribute->values[0]->unit}}
                @endif
            </div>
        </div>
    @endforeach
    @if($attributesCount == 0)
        <span class="ml-1">No Attributes</span>
    @endif
</div>
