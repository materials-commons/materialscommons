<ul>
    @foreach($attributes as $attribute)
        <li>
            {{$attribute->attr_name}}:
            @if(is_array($attribute->val["value"]))
                @json($attribute->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            @else
                {{$attribute->val["value"]}}
            @endif
            @if ($attribute->unit != "")
                {{$attribute->unit}}
            @endif
        </li>
    @endforeach
</ul>