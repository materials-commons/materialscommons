<dl class="row ml-2">
    @foreach($attributes as $attribute)
        <dt class="col-7">{{$attribute->attr_name}}:</dt>
        <dd class="col-4">
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
        </dd>
    @endforeach
</dl>