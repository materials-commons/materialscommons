<dl class="row ml-2">
    @foreach($activity->attributes as $attribute)
        <dt class="col-7">{{$attribute->name}}:</dt>
        <dd class="col-4">
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
        </dd>
    @endforeach
</dl>