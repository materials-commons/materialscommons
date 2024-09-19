<div>
    <div class="row">
        <div class="col-8">
            <div class="row ml-1">
                @if($details->isNumeric)
                    Numeric Attribute -
                @else
                    String Attribute -
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
        <div class="col-4 border">
            <div id="chart-{{$attrName}}-{{$attrType}}" data-chart-values='{{$jsonData}}'></div>
        </div>
    </div>
</div>