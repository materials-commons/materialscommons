<div id="{{slugify($name)}}" class="container border rounded" hx-target="this" hx-swap="outerHTML">
    <a href="#" hx-get="{{route('projects.attributes.close-details-by-name', [$project, $name])}}"
       class="row mb-2 float-right mr-2">Close</a>
    <div class="row">
        @if($details->isNumeric)
            Numeric Attribute - Min: {{$details->min}}, Max: {{$details->max}}, # Unique
            Values: {{$details->uniqueCount}}
        @else
            String Attribute - # Unique Values: {{$details->uniqueCount}}
        @endif

    </div>
    <div class="row">
        Showing first {{$details->values->count()}} unique values out of {{$details->uniqueCount}}.
    </div>
    <div class="row">
        <ul class="ml-4">
            @foreach($details->values as $value)
                <li>{{$value}}</li>
            @endforeach
        </ul>
    </div>
</div>