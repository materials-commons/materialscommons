<ul>
    @foreach($measurements as $attribute)
        <li>
            {{$attribute->attr_name}}: {{$attribute->val["value"]}}
            @if ($attribute->unit != "")
                {{$attribute->unit}}
            @endif
        </li>
    @endforeach
</ul>