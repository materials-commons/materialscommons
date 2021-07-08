<div id="{{slugify($name)}}" class="container border rounded" hx-target="this" hx-swap="outerHTML">
    <div class="row">
        <div class="col-12">
            <a href="#" hx-get="{{route('projects.attributes.close-details-by-name', [$project, $name])}}"
               class="mb-2 float-right mr-2">Close</a>
        </div>
    </div>
    <div class="row ml-1">
        @if($details->isNumeric)
            Numeric Attribute -
        @else
            String Attribute - # Unique Values: {{$details->uniqueCount}}
        @endif
    </div>
    @if($details->isNumeric)
        <div class="row">
            <ul class="ml-4">
                <li>
                    Min: {{$details->min}}
                </li>
                <li>
                    Max: {{$details->max}}
                </li>
            </ul>
        </div>
    @endif
    <div class="row ml-1">
        Showing {{$details->values->count()}} of {{$details->uniqueCount}} unique values -
    </div>
    <div class="row">
        <ul class="ml-4">
            @foreach($details->values as $value)
                <li>{{$value}}</li>
            @endforeach
        </ul>
    </div>
</div>