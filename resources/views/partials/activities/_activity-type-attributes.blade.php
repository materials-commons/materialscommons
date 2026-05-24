@php
    $attributesCount = 0;
@endphp
<div class="row ms-2">
    @foreach($attributes as $attribute)
        @php
            $attributesCount++;
        @endphp
        <div class="attribute-row row col-11 ms-1">
            <div class="col-7">{{$attribute->attr_name}}:</div>
            <div class="col-4">
                @if(is_array($attribute->val["value"]))
                    @json($attribute->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                @else
                    @if(blank($attribute->val["value"]))
                        No value
                    @else
                        {{$attribute->val["value"]}}
                    @endif
                @endif
                @if ($attribute->unit != "")
                    {{$attribute->unit}}
                @endif
            </div>
        </div>
    @endforeach
        @if($attributesCount == 0)
            <span class="ms-1">No Attributes</span>
        @endif
</div>
