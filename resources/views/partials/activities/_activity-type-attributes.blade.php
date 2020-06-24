<ul>
    @foreach($attributes as $attribute)
        <li>
            {{$attribute->attr_name}}
            : @json($attribute->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            @if ($attribute->unit != "")
                {{$attribute->unit}}
            @endif
        </li>
    @endforeach
</ul>