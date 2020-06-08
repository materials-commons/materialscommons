<ul>
    @foreach($activity->attributes as $attribute)
        <li>
            {{$attribute->name}}
            : @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            @if($attribute->values[0]->unit != "")
                {{$attribute->values[0]->unit}}
            @endif
        </li>
    @endforeach
</ul>