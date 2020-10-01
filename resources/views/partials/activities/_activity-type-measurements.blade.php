<dl class="row ml-2">
    @foreach($measurements as $attribute)
        <dt class="col-7">{{$attribute->attr_name}}:</dt>
        <dd class="col-4">
            @if(is_array($attribute->val["value"]))
                <ul class="list-unstyled">
                    @foreach($attribute->val["value"] as $entry)
                        <li>
                            @if(is_array($entry))
                                @foreach($entry as $key => $value)
                                    {{$key}}:
                                    @if(is_array($value))
                                        @json($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                                    @else
                                        {{$value}}
                                    @endif
                                @endforeach
                            @else
                                {{$entry}}
                            @endif
                        </li>
                    @endforeach
                </ul>
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