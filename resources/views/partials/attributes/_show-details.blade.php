<div id="{{slugify($name)}}" class="container border rounded" hx-target="this" hx-swap="outerHTML">
    <div class="row">
        <div class="col-12">
            <a href="#" hx-get="{{route('projects.attributes.close-details-by-name', [$project, $name])}}"
               class="mb-2 float-right me-2">Close</a>
        </div>
    </div>
    <div class="row ms-1">
        <h5>Attribute: <u>{{$name}}</u></h5>
    </div>
    @if(isset($entities))
        @if($entities->count() != 0)
            @if($entities[0]->category == "experimental")
                Samples
            @else
                Computations
            @endif
            <div class="row">
                <ul>
                    @foreach($entities as $entity)
                        <li><a href="{{route('projects.entities.show', [$project, $entity->id])}}">{{$entity->name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @elseif(isset($activities))
        @if($activities->count() != 0)
            @if($activities[0]->category == "experimental")
                Processes
            @else
                Activities
            @endif
            <div class="row">
                <ul>
                    @foreach($activities as $activity)
                        <li>{{$activity->name}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
    <div class="row ms-1">
        @if($details->isNumeric)
            Numeric Attribute -
        @else
            String Attribute - # Unique Values: {{$details->uniqueCount}}
        @endif
    </div>
    @if($details->isNumeric)
        <div class="row">
            <ul class="ms-4x">
                <li>
                    Min: {{$details->min}}
                </li>
                <li>
                    Max: {{$details->max}}
                </li>
            </ul>
        </div>
    @endif
    <div class="row ms-1">
        Showing {{$details->values->count()}} of {{$details->uniqueCount}} unique values -
    </div>
    <div class="row">
        <ul class="ms-4x">
            @foreach($details->values as $value)
                <li>{{$value}}</li>
            @endforeach
        </ul>
    </div>
</div>