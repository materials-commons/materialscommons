<div>
    <div class="row ml-1">
        @if($details->isNumeric)
            Numeric Attribute -
        @else
            String Attribute - # Unique Values: {{$details->uniqueCount}}
        @endif
    </div>
    @if($details->isNumeric)
        <div class="row">
            <ul class="ml-4x">
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
        <ul class="ml-4x">
            @foreach($details->values as $value)
                <li>{{$value}}</li>
            @endforeach
        </ul>
    </div>
</div>