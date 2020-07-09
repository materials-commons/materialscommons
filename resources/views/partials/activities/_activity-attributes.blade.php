<ul>
    @foreach($activity->attributes as $attribute)
        <li>
            {{$attribute->name}}:
            @if(is_array($attribute->values[0]->val["value"]))
                @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            @else
                {{$attribute->values[0]->val["value"]}}
            @endif
            @if($attribute->values[0]->unit != "")
                {{$attribute->values[0]->unit}}
            @endif
        </li>
    @endforeach
</ul>