<ul>
    @foreach($activity->attributes as $attribute)
        <li>
            {{$attribute->name}}
            : {{$attribute->values[0]->val["value"]}}
            @if($attribute->values[0]->unit != "")
                {{$attribute->values[0]->unit}}
            @endif
        </li>
    @endforeach
</ul>